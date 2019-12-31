<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\AutoComposer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

class AutoComposerTest extends TestCase
{
    use RemoveCacheFileTrait;

    /** @var vfsStreamContainer */
    private $projectDir;

    /** @var resource */
    private $errorStream;

    public function setUp()
    {
        $this->projectDir = vfsStream::setup('project', null, [
            'config' => [
                'autoload' => [],
            ],
            'cache' => [],
            'data' => [],
        ]);
        $this->errorStream = fopen('php://memory', 'w+');
    }

    public function tearDown()
    {
        if (is_resource($this->errorStream)) {
            fclose($this->errorStream);
        }
    }

    public function testIndicatesEnvironmentVariableNotSet()
    {
        putenv('COMPOSER_DEV_MODE');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testIndicatesEnvironmentVariableSetNull()
    {
        putenv('COMPOSER_DEV_MODE=0');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('Development mode was already disabled.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testIndicatesEnvironmentVariableSetOne()
    {
        putenv('COMPOSER_DEV_MODE=1');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->assertSame(1, $command());
    }

    public function testIndicatesEnvironmentVariableSetArbitrary()
    {
        putenv('COMPOSER_DEV_MODE=XX');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('COMPOSER_DEV_MODE set to unexpected value (\'XX\'). Nothing to do.' . PHP_EOL);
        $this->assertSame(1, $command());
    }
}

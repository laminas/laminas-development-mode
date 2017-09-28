<?php
/**
 * @link      http://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2017 Bernhard Miklautz <bernhard.miklautz@thincast.com>
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 *
 * Test based on EnableTest.php.
 */

namespace ZFTest\DevelopmentMode;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit_Framework_TestCase as TestCase;
use ZF\DevelopmentMode\Auto;

class AutoTest extends TestCase
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
        putenv("COMPOSER_DEV_MODE");
        $command = new Auto(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testIndicatesEnvironmentVariableSetNull()
    {
        putenv("COMPOSER_DEV_MODE=0");
        $command = new Auto(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('Development mode was already disabled.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testIndicatesEnvironmentVariableSetOne()
    {
        putenv("COMPOSER_DEV_MODE=1");
        $command = new Auto(vfsStream::url('project'), $this->errorStream);
        $this->assertSame(1, $command());
    }

    public function testIndicatesEnvironmentVariableSetArbitrary()
    {
        putenv("COMPOSER_DEV_MODE=XX");
        $command = new Auto(vfsStream::url('project'), $this->errorStream);
        $this->assertSame(1, $command());
    }
}

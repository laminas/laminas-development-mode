<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\AutoComposer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

use function fclose;
use function fopen;
use function putenv;

use const PHP_EOL;

class AutoComposerTest extends TestCase
{
    use RemoveCacheFileTrait;

    private vfsStreamContainer $projectDir;

    /** @var resource */
    private $errorStream;

    public function setUp(): void
    {
        $this->projectDir  = vfsStream::setup('project', null, [
            'config' => [
                'autoload' => [],
            ],
            'cache'  => [],
            'data'   => [],
        ]);
        $this->errorStream = fopen('php://memory', 'w+');
    }

    public function tearDown(): void
    {
        fclose($this->errorStream);
    }

    public function testIndicatesEnvironmentVariableNotSet(): void
    {
        putenv('COMPOSER_DEV_MODE');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testIndicatesEnvironmentVariableSetNull(): void
    {
        putenv('COMPOSER_DEV_MODE=0');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('Development mode was already disabled.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testIndicatesEnvironmentVariableSetOne(): void
    {
        putenv('COMPOSER_DEV_MODE=1');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->assertSame(1, $command());
    }

    public function testIndicatesEnvironmentVariableSetArbitrary(): void
    {
        putenv('COMPOSER_DEV_MODE=XX');
        $command = new AutoComposer(vfsStream::url('project'), $this->errorStream);
        $this->expectOutputString('COMPOSER_DEV_MODE set to unexpected value (\'XX\'). Nothing to do.' . PHP_EOL);
        $this->assertSame(1, $command());
    }
}

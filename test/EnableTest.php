<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Enable;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

use function fclose;
use function file_exists;
use function fopen;
use function fread;
use function fseek;
use function is_resource;

use const PHP_EOL;

class EnableTest extends TestCase
{
    use RemoveCacheFileTrait;

    private vfsStreamContainer $projectDir;

    /** @var resource */
    private $errorStream;

    private Enable $command;

    protected function setUp(): void
    {
        $this->projectDir  = vfsStream::setup('project', null, [
            'config' => [
                'autoload' => [],
            ],
            'cache'  => [],
            'data'   => [],
        ]);
        $this->errorStream = fopen('php://memory', 'w+');
        $this->command     = $this->getMockBuilder(Enable::class)
            ->setConstructorArgs([vfsStream::url('project'), $this->errorStream])
            ->setMethods(['supportsSymlinks'])
            ->getMock();
        $this->command->method('supportsSymlinks')->willReturn(false);
    }

    protected function tearDown(): void
    {
        if (is_resource($this->errorStream)) {
            fclose($this->errorStream);
        }
    }

    /**
     * @return bool|string
     */
    public function readErrorStream()
    {
        fseek($this->errorStream, 0);
        return fread($this->errorStream, 4096);
    }

    public function testIndicatesEnabledWhenDevelopmentConfigFileFound(): void
    {
        vfsStream::newFile(Enable::DEVEL_CONFIG)
            ->at($this->projectDir);
        $command = $this->command;
        $this->expectOutputString('Already in development mode!' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testRaisesErrorMessageIfMissingDevelopmentConfigDistFile(): void
    {
        $command = $this->command;
        $this->assertSame(1, $command());

        fseek($this->errorStream, 0);
        $this->assertStringContainsString(
            'MISSING "config/development.config.php.dist"',
            fread($this->errorStream, 4096)
        );
    }

    public function testRaisesErrorMessageIfApplicationConfigDoesNotReturnAnArrayDevelopmentModeIsNotEnabled(): void
    {
        vfsStream::newFile('config/development.config.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        vfsStream::newFile('config/application.config.php')
            ->at($this->projectDir)
            ->setContent('');
        $command = $this->command;
        $this->assertSame(1, $command(), 'Did not get expected return value from invoking enable');
        $this->assertFalse(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was copied to new file'
        );

        fseek($this->errorStream, 0);
        $this->assertStringContainsString(
            'Invalid configuration returned from config/application.config.php',
            fread($this->errorStream, 4096),
            'Unexpected error message'
        );
    }

    public function testWillCopyLocalAutoloadDistConfigIfPresent(): void
    {
        vfsStream::newFile('config/development.config.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        vfsStream::newFile('config/autoload/development.local.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        vfsStream::newFile('config/application.config.php')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        $command = $this->command;

        $this->expectOutputString('You are now in development mode.' . PHP_EOL);
        $result = $command();
        $this->assertSame(
            0,
            $result,
            'Did not get expected return value from invoking enable; errors: ' . $this->readErrorStream()
        );
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was not copied to new file'
        );
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/autoload/development.local.php'),
            'Distribution development local config was not copied to new file'
        );
    }

    public function testRemovesDefaultConfigCacheFileIfPresent(): void
    {
        vfsStream::newFile('config/development.config.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        $this->setUpDefaultCacheFile();
        $command = $this->command;

        $this->expectOutputString('You are now in development mode.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking enable');
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was not copied to new file'
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project') . '/cache/module-config-cache.php'),
            'Config cache file was not removed'
        );
    }

    public function testRemovesCustomConfigCacheFileIfPresent(): void
    {
        vfsStream::newFile('config/development.config.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        $this->setUpCustomCacheFile();
        $command = $this->command;

        $this->expectOutputString('You are now in development mode.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking enable');
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was not copied to new file'
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project') . '/cache/module-config-cache.custom.php'),
            'Config cache file was not removed'
        );
    }

    public function testDevelopmentModeEnabledWhenApplicationConfigNotFound(): void
    {
        vfsStream::newFile('config/development.config.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        $command = $this->command;

        $this->expectOutputString('You are now in development mode.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking enable');
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was not copied to new file'
        );
    }

    public function testRemovesDefaultMezzioConfigCacheFileIfPresent(): void
    {
        vfsStream::newFile('config/development.config.php.dist')
            ->at($this->projectDir)
            ->setContent('<' . "?php\nreturn [];");
        $this->setUpDefaultMezzioCacheFile();
        $command = $this->command;

        $this->expectOutputString('You are now in development mode.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking enable');
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was not copied to new file'
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project') . '/data/config-cache.php'),
            'Config cache file was not removed'
        );
    }
}

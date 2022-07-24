<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Disable;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

use function fclose;
use function file_exists;
use function file_put_contents;
use function fopen;
use function fread;
use function fseek;
use function is_resource;

use const PHP_EOL;

class DisableTest extends TestCase
{
    use RemoveCacheFileTrait;

    /** @var vfsStreamContainer */
    private $projectDir;

    /** @var resource */
    private $errorStream;

    /** @var string */
    private $configStub;

    /** @var Disable */
    private $command;

    protected function setUp(): void
    {
        $this->projectDir  = vfsStream::setup('project', null, [
            'config' => [
                'autoload' => [],
            ],
            'cache'  => [],
        ]);
        $this->errorStream = fopen('php://memory', 'w+');
        $this->configStub  = '<' . "?php\nreturn [];";
        $this->command     = new Disable(vfsStream::url('project'), $this->errorStream);
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

    public function testIndicatesDisabledWhenDevelopmentConfigFileNotFound(): void
    {
        $command = $this->command;
        $this->expectOutputString('Development mode was already disabled.' . PHP_EOL);
        $this->assertSame(0, $command());
    }

    public function testRaisesErrorMessageIfApplicationConfigDoesNotReturnAnArrayDevelopmentModeIsNotDisabled(): void
    {
        file_put_contents(vfsStream::url('project/config/development.config.php'), $this->configStub);
        vfsStream::newFile('config/application.config.php')
            ->at($this->projectDir)
            ->setContent('');
        $command = $this->command;
        $this->assertSame(1, $command(), 'Did not get expected return value from invoking disable');
        $this->assertTrue(
            file_exists(vfsStream::url('project') . '/config/development.config.php'),
            'Distribution development config was removed'
        );

        fseek($this->errorStream, 0);
        $this->assertStringContainsString(
            'Invalid configuration returned from config/application.config.php',
            fread($this->errorStream, 4096),
            'Unexpected error message'
        );
    }

    public function testWillRemoveLocalAutoloadDistConfigIfPresent(): void
    {
        file_put_contents(vfsStream::url('project/config/development.config.php'), $this->configStub);
        file_put_contents(vfsStream::url('project/config/autoload/development.local.php'), $this->configStub);
        file_put_contents(vfsStream::url('project/config/application.config.php'), $this->configStub);
        $command = $this->command;

        $this->expectOutputString('Development mode is now disabled.' . PHP_EOL);
        $result = $command();
        $this->assertSame(
            0,
            $result,
            'Did not get expected return value from invoking disable; errors: ' . $this->readErrorStream()
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project/config/development.config.php')),
            'Distribution development config was not removed'
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project/config/autoload/development.local.php')),
            'Distribution development local config was not removed'
        );
    }

    public function testRemovesDefaultConfigCacheFileIfPresent(): void
    {
        file_put_contents(vfsStream::url('project/config/development.config.php'), $this->configStub);
        $this->setUpDefaultCacheFile();
        $command = $this->command;

        $this->expectOutputString('Development mode is now disabled.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking disable');
        $this->assertFalse(
            file_exists(vfsStream::url('project/config/development.config.php')),
            'Distribution development config was not removed'
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project') . '/cache/module-config-cache.php'),
            'Config cache file was not removed'
        );
    }

    public function testRemovesCustomConfigCacheFileIfPresent(): void
    {
        file_put_contents(vfsStream::url('project/config/development.config.php'), $this->configStub);
        $this->setUpCustomCacheFile();
        $command = $this->command;

        $this->expectOutputString('Development mode is now disabled.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking disable');
        $this->assertFalse(
            file_exists(vfsStream::url('project/config/development.config.php')),
            'Distribution development config was not removed'
        );
        $this->assertFalse(
            file_exists(vfsStream::url('project') . '/cache/module-config-cache.custom.php'),
            'Config cache file was not removed'
        );
    }

    public function testDevelopmentModeDisabledWhenApplicationConfigNotFound(): void
    {
        file_put_contents(vfsStream::url('project/config/development.config.php'), $this->configStub);
        $command = $this->command;

        $this->expectOutputString('Development mode is now disabled.' . PHP_EOL);
        $this->assertSame(0, $command(), 'Did not get expected return value from invoking disable');
        $this->assertFalse(
            file_exists(vfsStream::url('project/config/development.config.php')),
            'Distribution development config was not removed'
        );
    }
}

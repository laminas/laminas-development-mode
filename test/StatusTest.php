<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Status;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

use function ob_get_clean;
use function ob_start;

class StatusTest extends TestCase
{
    private vfsStreamContainer $projectDir;

    protected function setUp(): void
    {
        $this->projectDir = vfsStream::setup('project');
    }

    public function testIndicatesEnabledWhenDevelopmentConfigFileFound(): void
    {
        vfsStream::newFile(Status::DEVEL_CONFIG)
            ->at($this->projectDir);
        $status = new Status(vfsStream::url('project'));
        ob_start();
        $status();
        $output = ob_get_clean();
        $this->assertStringContainsString('ENABLED', $output);
    }

    public function testIndicatesDisabledWhenDevelopmentConfigFileNotFound(): void
    {
        $status = new Status();
        ob_start();
        $status();
        $output = ob_get_clean();
        $this->assertStringContainsString('DISABLED', $output);
    }
}

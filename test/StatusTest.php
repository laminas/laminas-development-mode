<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Status;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamContainer;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /** @var vfsStreamContainer */
    private $projectDir;

    protected function setUp()
    {
        $this->projectDir = vfsStream::setup('project');
    }

    public function testIndicatesEnabledWhenDevelopmentConfigFileFound()
    {
        vfsStream::newFile(Status::DEVEL_CONFIG)
            ->at($this->projectDir);
        $status = new Status(vfsStream::url('project'));
        ob_start();
        $status();
        $output = ob_get_clean();
        $this->assertContains('ENABLED', $output);
    }

    public function testIndicatesDisabledWhenDevelopmentConfigFileNotFound()
    {
        $status = new Status();
        ob_start();
        $status();
        $output = ob_get_clean();
        $this->assertContains('DISABLED', $output);
    }
}

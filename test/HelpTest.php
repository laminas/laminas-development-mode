<?php


namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Help;
use PHPUnit\Framework\TestCase;

class HelpTest extends TestCase
{
    public function testWritesToStdoutWhenCalledWithNoArguments()
    {
        $help = new Help();
        ob_start();
        $help();
        $output = ob_get_clean();
        $this->assertStringContainsString('Enable/Disable development mode.', $output);
    }

    public function testCanProvideAlternateStream()
    {
        $stream = fopen('php://memory', 'w+');
        $help = new Help();
        $help($stream);
        fseek($stream, 0);
        $this->assertStringContainsString('Enable/Disable development mode.', fread($stream, 4096));
    }
}

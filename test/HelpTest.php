<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Help;
use PHPUnit\Framework\TestCase;

use function fopen;
use function fread;
use function fseek;
use function ob_get_clean;
use function ob_start;

final class HelpTest extends TestCase
{
    public function testWritesToStdoutWhenCalledWithNoArguments(): void
    {
        $help = new Help();
        ob_start();
        $help();
        $output = ob_get_clean();
        self::assertIsString($output);
        self::assertStringContainsString('Enable/Disable development mode.', $output);
    }

    public function testCanProvideAlternateStream(): void
    {
        $stream = fopen('php://memory', 'w+');
        self::assertNotFalse($stream);
        $help = new Help();
        $help($stream);
        fseek($stream, 0);
        $output = fread($stream, 4096);
        self::assertNotFalse($output);
        self::assertStringContainsString('Enable/Disable development mode.', $output);
    }
}

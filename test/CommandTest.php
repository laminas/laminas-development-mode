<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use Laminas\DevelopmentMode\Command;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Command::class)]
final class CommandTest extends TestCase
{
    public function testExitCodeWithNoArguments(): void
    {
        self::assertSame(1, (new Command())([]));
    }

    public function testExitCodeWithHelpArgument(): void
    {
        self::assertSame(0, (new Command())(['-h']));
        self::assertSame(0, (new Command())(['--help']));
    }
}

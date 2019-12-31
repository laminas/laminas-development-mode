<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DevelopmentMode;

class Command
{
    /**
     * Handle the CLI arguments.
     *
     * @param array $arguments
     * @return int
     */
    public function __invoke(array $arguments)
    {
        $help = new Help();

        // Called without arguments
        if (count($arguments) < 1) {
            fwrite(STDERR, 'No arguments provided.' . PHP_EOL . PHP_EOL);
            $help(STDERR);
            return 1;
        }

        $argument = array_shift($arguments);

        switch ($argument) {
            case '-h':
            case '--help':
                $help();
                return 0;
            case 'disable':
                $disable = new Disable();
                return $disable();
            case 'enable':
                $enable = new Enable();
                return $enable();
            case 'status':
                $status = new Status();
                return $status();
            case 'auto-composer':
                $auto = new AutoComposer();
                return $auto();
            default:
                fwrite(STDERR, 'Unrecognized argument.' . PHP_EOL . PHP_EOL);
                $help(STDERR);
                return 1;
        }
    }
}

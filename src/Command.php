<?php
/**
 * @link      http://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\DevelopmentMode;

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
            case 'auto_composer':
                $composer_dev_mode = getenv("COMPOSER_DEV_MODE");
                if ($composer_dev_mode != "") {
                    if ($composer_dev_mode == "0") {
                        $disable = new Disable();
                        return $disable();
                    } else {
                        $enable = new Enable();
                        return $enable();
                    }
                }
                // not running under composer
                fwrite(STDOUT, 'COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL);
                return 0;
            default:
                fwrite(STDERR, 'Unrecognized argument.' . PHP_EOL . PHP_EOL);
                $help(STDERR);
                return 1;
        }
    }
}

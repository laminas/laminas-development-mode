<?php
/**
 * @link      http://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\DevelopmentMode;

use RuntimeException;

class Enable
{
    use ConfigDiscoveryTrait;

    /**
     * Enable development mode.
     *
     * @return int
     */
    public function __invoke()
    {
        if (file_exists('config/development.config.php')) {
            // nothing to do
            echo 'Already in development mode!', PHP_EOL;
            return 0;
        }

        if (! file_exists('config/development.config.php.dist')) {
            fwrite(
                STDERR,
                'MISSING "config/development.config.php.dist". Could not switch to development mode!' . PHP_EOL
            );
            return 1;
        }

        copy('config/development.config.php.dist', 'config/development.config.php');

        if (file_exists('config/autoload/development.local.php.dist')) {
            // optional application config override
            copy('config/autoload/development.local.php.dist', 'config/autoload/development.local.php');
        }

        try {
            $this->removeConfigCacheFile($this->getConfigCacheFile());
        } catch (RuntimeException $e) {
            fwrite(STDERR, $e->getMessage());
            return 1;
        }

        echo 'You are now in development mode.', PHP_EOL;
        return 0;
    }
}

<?php
/**
 * @link      http://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\DevelopmentMode;

use RuntimeException;

class Disable
{
    use ConfigDiscoveryTrait;

    /**
     * Disable development mode.
     *
     * @return int
     */
    public function __invoke()
    {
        if (! file_exists('config/development.config.php')) {
            // nothing to do
            echo 'Development mode was already disabled.', PHP_EOL;
            return 0;
        }

        if (file_exists('config/autoload/development.local.php')) {
            // optional application config override
            unlink('config/autoload/development.local.php');
        }

        unlink('config/development.config.php');

        try {
            $this->removeConfigCacheFile($this->getConfigCacheFile());
        } catch (RuntimeException $e) {
            fwrite(STDERR, $e->getMessage());
            return 1;
        }

        echo 'Development mode is now disabled.', PHP_EOL;
        return 0;
    }
}

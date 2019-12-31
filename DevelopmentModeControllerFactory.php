<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DevelopmentMode;

class DevelopmentModeControllerFactory
{
    public function __invoke($controllers)
    {
        $configCacheDir = null;
        $configCacheKey = null;
        $services       = $controllers->getServiceLocator();

        if ($services->has('ApplicationConfig')) {
            $config = $services->get('ApplicationConfig');
            if (isset($config['cache_dir']) && ! empty($config['cache_dir'])) {
                $configCacheDir = $config['cache_dir'];
            }
            if (isset($config['config_cache_key']) && ! empty($config['config_cache_key'])) {
                $configCacheKey = $config['config_cache_key'];
            }
        }

        return new DevelopmentModeController($configCacheDir, $configCacheKey);
    }
}

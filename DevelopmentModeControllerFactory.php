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
            if (isset($config['module_listener_options']['cache_dir'])
                 && !empty($config['module_listener_options']['cache_dir'])
            ) {
                $configCacheDir = $config['module_listener_options']['cache_dir'];
            }
            if (isset($config['module_listener_options']['config_cache_key'])
                && !empty($config['module_listener_options']['config_cache_key'])
            ) {
                $configCacheKey = $config['module_listener_options']['config_cache_key'];
            }
        }

        return new DevelopmentModeController($configCacheDir, $configCacheKey);
    }
}

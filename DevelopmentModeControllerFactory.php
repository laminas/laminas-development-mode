<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\DevelopmentMode;

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

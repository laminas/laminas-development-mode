<?php
/**
 * @link      http://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\DevelopmentMode;

use RuntimeException;

/**
 * Shared functionality for the Disable/Enable commands.
 */
trait ConfigDiscoveryTrait
{
    /**
     * @var null|array
     */
    private $applicationConfig;

    /**
     * @var string Base name for configuration cache.
     */
    private $configCacheBase = 'module-config-cache';

    /**
     * @var null|string
     */
    private $configCacheDir;

    /**
     * @var null|string
     */
    private $configCacheKey;

    /**
     * Removes the application configuration cache file, if present.
     *
     * @param string $configCacheFile
     */
    private function removeConfigCacheFile($configCacheFile)
    {
        if ($configCacheFile && file_exists($configCacheFile)) {
            unlink($configCacheFile);
        }
    }
    
    /**
     * Retrieve the config cache file, if any.
     *
     * @return false|string
     */
    private function getConfigCacheFile()
    {
        $configCacheDir = $this->getConfigCacheDir();
        $configCacheKey = $this->getConfigCacheKey();
    
        if (empty($configCacheDir)) {
            return false;
        }
    
        $path = sprintf('%s/%s.', $configCacheDir, $this->configCacheBase);
    
        if (! empty($configCacheKey)) {
            $path .= $configCacheKey . '.';
        }
    
        return $path . 'php';
    }
    
    /**
     * Return the configured configuration cache directory, if any.
     *
     * @return null|string
     */
    public function getConfigCacheDir()
    {
        if ($this->configCacheDir) {
            return $this->configCacheDir;
        }
    
        $config = $this->getApplicationConfig();
        if (isset($config['module_listener_options']['cache_dir'])
            && ! empty($config['module_listener_options']['cache_dir'])
        ) {
            $this->configCacheDir = $config['module_listener_options']['cache_dir'];
        }
    
        return $this->configCacheDir;
    }
    
    /**
     * Return the configured configuration cache key, if any.
     *
     * @return null|string
     */
    private function getConfigCacheKey()
    {
        if ($this->configCacheKey) {
            return $this->configCacheKey;
        }
    
        $config = $this->getApplicationConfig();
        if (isset($config['module_listener_options']['config_cache_key'])
            && ! empty($config['module_listener_options']['config_cache_key'])
        ) {
            $this->configCacheKey = $config['module_listener_options']['config_cache_key'];
        }
    
        return $this->configCacheKey;
    }
    
    /**
     * Return the application configuration.
     *
     * Raises an exception if unable to retrieve the configuration, or if it is
     * not an array.
     *
     * @return array
     * @throws RuntimeException if config/application.config.php cannot be
     *     found
     * @throws RuntimeException if config/application.config.php does not
     *     return an array
     */
    function getApplicationConfig()
    {
        if ($this->applicationConfig) {
            return $this->applicationConfig;
        }
    
        if (! file_exists('config/application.config.php')) {
            throw new RuntimeException(
                'Cannot locate config/application.config.php; are you in the' . PHP_EOL
                . 'application root, and is this a zendframework application?' . PHP_EOL
            );
        }
    
        $config = include 'config/application.config.php';

        if (! is_array($config)) {
            throw new RuntimeException(
                'Invalid configuration returned from config/application.config.php;' . PHP_EOL
                . 'is this a zendframework application?' . PHP_EOL
            );
        }

        return $config;
    }
}

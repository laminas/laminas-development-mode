<?php

declare(strict_types=1);

namespace Laminas\DevelopmentMode;

use RuntimeException;

use function assert;
use function file_exists;
use function is_array;
use function is_string;
use function sprintf;
use function unlink;

use const PHP_EOL;

/**
 * Shared functionality for the Disable/Enable commands.
 *
 * @internal
 */
trait ConfigDiscoveryTrait
{
    /** @var null|array */
    private $applicationConfig;

    /** @var string */
    private $applicationConfigPath = 'config/application.config.php';

    /** @var string */
    private $mezzioConfigPath = 'config/config.php';

    /** @var string Base name for configuration cache. */
    private $configCacheBase = 'module-config-cache';

    /**
     * Removes the application configuration cache file, if present.
     */
    private function removeConfigCacheFile(): void
    {
        $configCacheFile = $this->getConfigCacheFile();

        if ($configCacheFile === false || ! file_exists($configCacheFile)) {
            return;
        }

        unlink($configCacheFile);
    }

    /**
     * Retrieve the config cache file, if any.
     */
    private function getConfigCacheFile(): false|string
    {
        $config = $this->getApplicationConfig();

        if (isset($config['config_cache_path']) && is_string($config['config_cache_path'])) {
            return $config['config_cache_path'];
        }

        $configCacheDir = $this->getConfigCacheDir();

        if ($configCacheDir === null) {
            return false;
        }

        $path = sprintf('%s/%s.', $configCacheDir, $this->configCacheBase);

        $configCacheKey = $this->getConfigCacheKey();
        if ($configCacheKey !== null) {
            $path .= $configCacheKey . '.';
        }

        return $path . 'php';
    }

    /**
     * Return the configured configuration cache directory, if any.
     */
    private function getConfigCacheDir(): string|null
    {
        $config  = $this->getApplicationConfig();
        $options = $config['module_listener_options'] ?? [];
        assert(is_array($options));
        /** @psalm-var mixed $directory */
        $directory = $options['cache_dir'] ?? null;

        return is_string($directory) && $directory !== '' ? $directory : null;
    }

    /**
     * Return the configured configuration cache key, if any.
     */
    private function getConfigCacheKey(): null|string
    {
        $config  = $this->getApplicationConfig();
        $options = $config['module_listener_options'] ?? [];
        assert(is_array($options));
        $key = $options['config_cache_key'] ?? null;
        if (! is_string($key) || $key === '') {
            return null;
        }

        return $key;
    }

    /**
     * Return the application configuration.
     *
     * Raises an exception if retrieved configuration is not an array.
     *
     * @return array
     * @throws RuntimeException If config/application.config.php does not
     *     return an array.
     */
    private function getApplicationConfig()
    {
        if (null !== $this->applicationConfig) {
            return $this->applicationConfig;
        }

        $configFile = isset($this->projectDir)
            ? sprintf('%s/%s', $this->projectDir, $this->applicationConfigPath)
            : $this->applicationConfigPath;

        $configFile = file_exists($configFile) ? $configFile : $this->mezzioConfigPath;

        if (! file_exists($configFile)) {
            $this->applicationConfig = [];
            return $this->applicationConfig;
        }

        $this->applicationConfig = include $configFile;

        if (! is_array($this->applicationConfig)) {
            throw new RuntimeException(
                'Invalid configuration returned from config/application.config.php or config/config.php;' . PHP_EOL
                . 'is this a Laminas or Laminas Mezzio application?' . PHP_EOL
            );
        }

        return $this->applicationConfig;
    }
}

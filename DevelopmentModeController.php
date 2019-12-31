<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DevelopmentMode;

use Laminas\Console\Request as ConsoleRequest;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;

class DevelopmentModeController extends AbstractActionController
{
    const CONFIG_CACHE_BASE = 'module-config-cache';

    /**
     * @param string Configuration cache directory, if any
     */
    private $configCacheDir;

    /**
     * @param string Configuration cache key, if any
     */
    private $configCacheKey;

    /**
     * @param null|string $configCacheDir
     * @param null|string $configCacheKey
     */
    public function __construct($configCacheDir, $configCacheKey)
    {
        $this->configCacheDir = $configCacheDir;
        $this->configCacheKey = $configCacheKey;
    }

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', function ($e) {
            $request = $e->getRequest();
            if (!$request instanceof ConsoleRequest) {
                throw new \RuntimeException(sprintf(
                    '%s can only be executed in a console environment',
                    __CLASS__
                ));
            }
        }, 100);
        return $this;
    }

    public function enableAction()
    {
        if (file_exists('config/development.config.php')) {
            // nothing to do
            return "Already in development mode!\n";
        }

        if (!file_exists('config/development.config.php.dist')) {
            return "MISSING \"config/development.config.php.dist\". Could not switch to development mode!\n";
        }

        copy('config/development.config.php.dist', 'config/development.config.php');

        if (file_exists('config/autoload/development.local.php.dist')) {
            // optional application config override
            copy('config/autoload/development.local.php.dist', 'config/autoload/development.local.php');
        }

        $configCacheFile = $this->getConfigCacheFile();
        if ($configCacheFile && file_exists($configCacheFile)) {
            unlink($configCacheFile);
        }

        return "You are now in development mode.\n";
    }

    public function disableAction()
    {
        if (!file_exists('config/development.config.php')) {
            // nothing to do
            return "Development mode was already disabled.\n";
        }

        if (file_exists('config/autoload/development.local.php')) {
            // optional application config override
            unlink('config/autoload/development.local.php');
        }

        unlink('config/development.config.php');
        return "Development mode is now disabled.\n";
    }

    /**
     * Retrieve the config cache file, if any.
     *
     * @return false|string
     */
    private function getConfigCacheFile()
    {
        if (empty($this->configCacheDir)) {
            return false;
        }

        $path = sprintf('%s/%s.', $this->configCacheDir, self::CONFIG_CACHE_BASE);

        if (! empty($this->configCacheKey)) {
            $path .= $this->configCacheKey . '.';
        }

        return $path . 'php';
    }
}
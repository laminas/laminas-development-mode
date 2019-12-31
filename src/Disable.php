<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DevelopmentMode;

use RuntimeException;

class Disable
{
    use ConfigDiscoveryTrait;

    const DEVEL_CONFIG = 'config/development.config.php';
    const DEVEL_LOCAL  = 'config/autoload/development.local.php';

    /**
     * @var resource
     */
    private $errorStream;

    /**
     * @param string Path to project.
     */
    private $projectDir;

    /**
     * @param string $projectDir Location to resolve project from.
     * @param null|resource $errorStream Stream to which to write errors; defaults to STDERR
     */
    public function __construct($projectDir = '', $errorStream = null)
    {
        $this->projectDir = $projectDir;
        $this->errorStream = is_resource($errorStream) ? $errorStream : STDERR;
    }

    /**
     * Disable development mode.
     *
     * @return int
     */
    public function __invoke()
    {
        $develConfig = $this->projectDir
            ? sprintf('%s/%s', $this->projectDir, self::DEVEL_CONFIG)
            : self::DEVEL_CONFIG;
        if (! file_exists($develConfig)) {
            // nothing to do
            echo 'Development mode was already disabled.', PHP_EOL;
            return 0;
        }

        try {
            $this->removeConfigCacheFile();
        } catch (RuntimeException $ex) {
            fwrite($this->errorStream, $ex->getMessage());
            return 1;
        }

        $develLocalConfig = $this->projectDir
            ? sprintf('%s/%s', $this->projectDir, self::DEVEL_LOCAL)
            : self::DEVEL_LOCAL;
        if (file_exists($develLocalConfig)) {
            // optional application config override
            unlink($develLocalConfig);
        }

        unlink($develConfig);

        echo 'Development mode is now disabled.', PHP_EOL;
        return 0;
    }
}

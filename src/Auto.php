<?php
/**
 * @link      http://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2017 Bernhard Miklautz <bernhard.miklautz@thincast.com>
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZF\DevelopmentMode;

class Auto
{
    const COMPOSER_DEV_MODE = 'COMPOSER_DEV_MODE';

    /**
     * @var value of COMPOSER_DEV_MODE
     */
    private $composer_dev_mode;

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
        $this->composer_dev_mode = getenv(self::COMPOSER_DEV_MODE);
        $this->projectDir = $projectDir;
        $this->errorStream = is_resource($errorStream) ? $errorStream : STDERR;
    }

    /**
     * Enable or disable developer mode based on composer_dev_mode.
     *
     * @return int
     */
    public function __invoke()
    {
        if ($this->composer_dev_mode != "") {
            if ($this->composer_dev_mode == "0") {
                $disable = new Disable($this->projectDir, $this->errorStream);
                return $disable();
            } else {
                $enable = new Enable($this->projectDir, $this->errorStream);
                return $enable();
            }
        }
        // not running under composer
        echo 'COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL;
        return 0;
    }
}

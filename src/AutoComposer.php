<?php
/**
 * @see       https://github.com/zfcampus/zf-development-mode for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zfcampus/zf-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace ZF\DevelopmentMode;

/**
 * Automatically switch to and from development mode based on type of composer
 * install/update used.
 *
 * If a development install is being performed (`--dev` flag or absence of
 * `--no-dev` flag), then it will enable development mode. Otherwise, it
 * disables it. This is determined by the value of the `COMPOSER_DEV_MODE`
 * environment variable that Composer sets.
 *
 * If the `COMPOSER_DEV_MODE` environment variable is missing, then the command
 * does nothing.
 */
class AutoComposer
{
    const COMPOSER_DEV_MODE = 'COMPOSER_DEV_MODE';

    /**
     * @var value of COMPOSER_DEV_MODE
     */
    private $composerDevMode;

    /**
     * @var resource
     */
    private $errorStream;

    private $expectedValues = [
        '0', // production mode
        '1', // development mode
    ];

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
        $this->composerDevMode = getenv(self::COMPOSER_DEV_MODE);
        $this->projectDir = $projectDir;
        $this->errorStream = is_resource($errorStream) ? $errorStream : STDERR;
    }

    /**
     * Enable or disable developer mode based on composerDevMode.
     *
     * @return int
     */
    public function __invoke()
    {
        if ($this->composerDevMode === '' || $this->composerDevMode === false) {
            // Not running under composer; do nothing.
            echo 'COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL;
            return 0;
        }

        if ($this->composerDevMode === '0') {
            $disable = new Disable($this->projectDir, $this->errorStream);
            return $disable();
        }

        if ($this->composerDevMode === '1') {
            $enable = new Enable($this->projectDir, $this->errorStream);
            return $enable();
        }

        printf(
            'COMPOSER_DEV_MODE set to unexpected value (%s). Nothing to do.%s',
            var_export($this->composerDevMode, true),
            PHP_EOL
        );
        return 1;
    }
}

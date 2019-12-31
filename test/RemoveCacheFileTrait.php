<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\DevelopmentMode;

use org\bovigo\vfs\vfsStream;

trait RemoveCacheFileTrait
{
    public function setUpDefaultCacheFile()
    {
        $base = vfsStream::url('project');
        $config = <<< EOC
<?php
return [
    'module_listener_options' => [
        'cache_dir' => '{$base}/cache',
    ],
];
EOC;

        file_put_contents(vfsStream::url('project/config/application.config.php'), $config);
        file_put_contents(vfsStream::url('project/cache/module-config-cache.php'), '<' . "?php\nreturn [];");
    }

    public function setUpCustomCacheFile()
    {
        $base = vfsStream::url('project');
        $config = <<< EOC
<?php
return [
    'module_listener_options' => [
        'cache_dir' => '{$base}/cache',
        'config_cache_key' => 'custom',
    ],
];
EOC;

        file_put_contents(vfsStream::url('project/config/application.config.php'), $config);
        file_put_contents(vfsStream::url('project/cache/module-config-cache.custom.php'), '<' . "?php\nreturn [];");
    }

    public function setUpDefaultMezzioCacheFile()
    {
        $cache = vfsStream::url('project/data/config-cache.php');
        $config = <<< EOC
<?php
return [
    'config_cache_path' => '{$cache}',
];
EOC;

        file_put_contents(vfsStream::url('project/config/application.config.php'), $config);
        file_put_contents($cache, '<' . "?php\nreturn [];");
    }
}

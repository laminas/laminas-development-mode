<?php

declare(strict_types=1);

namespace LaminasTest\DevelopmentMode;

use org\bovigo\vfs\vfsStream;

use function file_put_contents;

trait RemoveCacheFileTrait
{
    public function setUpDefaultCacheFile(): void
    {
        $base   = vfsStream::url('project');
        $config = <<<EOC
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

    public function setUpCustomCacheFile(): void
    {
        $base   = vfsStream::url('project');
        $config = <<<EOC
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

    public function setUpDefaultMezzioCacheFile(): void
    {
        $cache  = vfsStream::url('project/data/config-cache.php');
        $config = <<<EOC
            <?php
            return [
                'config_cache_path' => '{$cache}',
            ];
            EOC;

        file_put_contents(vfsStream::url('project/config/application.config.php'), $config);
        file_put_contents($cache, '<' . "?php\nreturn [];");
    }
}

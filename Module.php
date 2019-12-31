<?php

/**
 * @see       https://github.com/laminas/laminas-development-mode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-development-mode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-development-mode/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\DevelopmentMode;

use Laminas\Console\Adapter\AdapterInterface as Console;

class Module
{
    public function getConfig()
    {
        return [
            'controllers' => [
                'factories' => [
                    'Laminas\DevelopmentMode\DevelopmentModeController' =>
                        'Laminas\DevelopmentMode\DevelopmentModeControllerFactory',
                ],
            ],
            'console' => [
                'router' => [
                    'routes' => [
                        'development-disable' => [
                            'options' => [
                                'route' => 'development disable',
                                'defaults' => [
                                    'controller' => 'Laminas\DevelopmentMode\DevelopmentModeController',
                                    'action'     => 'disable',
                                ],
                            ],
                        ],
                        'development-enable' => [
                            'options' => [
                                'route' => 'development enable',
                                'defaults' => [
                                    'controller' => 'Laminas\DevelopmentMode\DevelopmentModeController',
                                    'action'     => 'enable',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Return the console usage for this module
     *
     * @param Console $console
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            'development enable'  => 'Enable development mode (do not use in production)',
            'development disable' => 'Disable development mode'
        ];
    }
}

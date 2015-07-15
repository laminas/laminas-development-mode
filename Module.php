<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2013 Rob Allen (http://19ft.com)
 */

namespace ZF\DevelopmentMode;

use Zend\Console\Adapter\AdapterInterface as Console;

class Module
{
    public function getConfig()
    {
        return [
            'controllers' => [
                'factories' => [
                    'ZF\DevelopmentMode\DevelopmentModeController' =>
                        'ZF\DevelopmentMode\DevelopmentModeControllerFactory',
                ],
            ],
            'console' => [
                'router' => [
                    'routes' => [
                        'development-disable' => [
                            'options' => [
                                'route' => 'development disable',
                                'defaults' => [
                                    'controller' => 'ZF\DevelopmentMode\DevelopmentModeController',
                                    'action'     => 'disable',
                                ],
                            ],
                        ],
                        'development-enable' => [
                            'options' => [
                                'route' => 'development enable',
                                'defaults' => [
                                    'controller' => 'ZF\DevelopmentMode\DevelopmentModeController',
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

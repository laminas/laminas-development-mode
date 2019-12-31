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
        return array(
            'controllers' => array(
                'invokables' => array(
                    'Laminas\DevelopmentMode\DevelopmentModeController' => 'Laminas\DevelopmentMode\DevelopmentModeController',
                ),
            ),
            'console' => array(
                'router' => array(
                    'routes' => array(
                        'development-disable' => array(
                            'options' => array(
                                'route' => 'development disable',
                                'defaults' => array(
                                    'controller' => 'Laminas\DevelopmentMode\DevelopmentModeController',
                                    'action'     => 'disable',
                                ),
                            ),
                        ),
                        'development-enable' => array(
                            'options' => array(
                                'route' => 'development enable',
                                'defaults' => array(
                                    'controller' => 'Laminas\DevelopmentMode\DevelopmentModeController',
                                    'action'     => 'enable',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
    
    /**
     * Return the console usage for this module
     *
     * @param Console $console            
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return array(
            'development enable'  => 'Enable development mode (do not use in production)',
            'development disable' => 'Disable development mode'
        );
    }
}

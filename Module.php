<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2013 Rob Allen (http://19ft.com)
 */

namespace NFDevelopmentMode;

class Module
{
    public function getConfig()
    {
        return array(
            'controllers' => array(
                'invokables' => array(
                    'NFDevelopmentMode\DevelopmentModeController' => 'NFDevelopmentMode\DevelopmentModeController',
                ),
            ),
            'console' => array(
                'router' => array(
                    'routes' => array(
                        'development-disable' => array(
                            'options' => array(
                                'route' => 'development disable',
                                'defaults' => array(
                                    'controller' => 'NFDevelopmentMode\DevelopmentModeController',
                                    'action'     => 'disable',
                                ),
                            ),
                        ),
                        'development-enable' => array(
                            'options' => array(
                                'route' => 'development enable',
                                'defaults' => array(
                                    'controller' => 'NFDevelopmentMode\DevelopmentModeController',
                                    'action'     => 'enable',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}

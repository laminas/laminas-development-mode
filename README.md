#NFDevelopmentMode

This ZF2 development mode module is an extraction of the Apigility Skeleton's Development Mode Controller to its own module for use in ZF2 applications.

##Installation with Composer

1. Add `"19ft/nf-development-mode": "1.*"` to the `"require"` section your `composer.json` file and run `php composer.phar update`.
2. Copy `development.config.dist` to `config/development.config.dist` and edit as required. Commit this file to your VCS.
3. Add any development modules to the `"require-dev"` section of your application's `composer.json`. e.g:
   
        "zendframework/zend-developer-tools": "dev-master",
        "zendframework/zftool": "dev-master"
        
    and run `composer.update`.
4. If you're using Zend Developer Tools, Copy `./vendor/zendframework/zend-developer-tools/config/zenddevelopertools.local.php.dist` to `./config/autoload/zenddevelopertools.local.php`. Change any settings in it according to your needs.
5. Add `'NFDevelopmentMode'` to the list of Modules in your `config/application.config.php` file.
6. In `public/index.php`, replace these lines:

        // Run the application!
        Zend\Mvc\Application::init(require 'config/application.config.php')->run();

    with

        // Config
        $appConfig = include APPLICATION_PATH . '/config/application.config.php';

        if (file_exists(APPLICATION_PATH . '/config/development.config.php')) {
            $appConfig = Zend\Stdlib\ArrayUtils::merge($appConfig, include APPLICATION_PATH . '/config/development.config.php');
        }

        // Run the application!
        Zend\Mvc\Application::init($appConfig)->run();


## To enable development mode

    cd path/to/install
    php public/index.php development enable

## To disable development mode

    cd path/to/install
    php public/index.php development disable


Note: Don't run development mode on your production server.


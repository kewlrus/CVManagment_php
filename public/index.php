<?php
//ini_set('include_path', "/usr/www/users/ivanev/cvm/zf2/library/");
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
// phpinfo();
chdir(dirname(__DIR__));
//echo "Asd". getenv('REDIRECT_ZF2_PATH');
// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

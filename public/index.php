<?php
//ini_set('session.cookie_domain', 'web.jige.la.me');
session_start();
use Pest\Application;
use Pest\Util;

define('PATH_BASE', realpath('../') . '/');
define('API_ROOT',realpath('./').'/');
define('PATH_LIBRARY', PATH_BASE . 'Library/');
define('SAE_ROOT','http://1.mallschoolwx.sinaapp.com/');

require PATH_LIBRARY . 'Pest/Application.php';
$config = array(
        'db' => array(
                'driver' => 'Pdo',
                'host' => 'localhost',
                'username' => 'root',
                'password' => 'mallschool',
                'dbname' => 'mallschool'
        ),
        'apiDir' => PATH_BASE,
);
set_include_path(
        implode(PATH_SEPARATOR, 
                array(
                        $config['apiDir'],
                        get_include_path()
                )));
$app = new Application($config);
$app->run();

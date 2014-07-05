<?php
use Pest\Application;
define('PATH_BASE', realpath('../') . '/');
define('PATH_LIBRARY', PATH_BASE . 'Library/');

require PATH_LIBRARY . 'Pest/Application.php';
$config = array(
        'db' => array(
                'name' => 'olege'
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
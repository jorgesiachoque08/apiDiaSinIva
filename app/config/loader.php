<?php
use Phalcon\Loader;

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader = new Loader();
$loader->registerDirs(
    array(
        $config->application->modelsDir,
        $config->application->controllersDir,
        $config->application->componentesDir
    )
    );

$loader->registerNamespaces(
    [
        'App\Models' =>  $config->application->modelsDir
    ]
);

$loader->register();
    


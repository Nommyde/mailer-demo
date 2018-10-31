<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require_once __DIR__ . '/vendor/autoload.php';


try {
    $container = new ContainerBuilder();
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
    $loader->load('config.dev.yaml');

    /** @var Application $app */
    $app = $container->get('app');
    $app->run(null, $container->get('output'));
} catch (Throwable $e) {
    echo 'Fatal error: ' . $e->getMessage();
}

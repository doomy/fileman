<?php

declare(strict_types=1);

use Nette\Bootstrap\Configurator;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->setDebugMode((bool) ($_ENV['APP_DEBUG'] ?? false));
$configurator->addConfig(__DIR__ . '/../config/config.neon');

$container = $configurator->createContainer();
$app = $container->getByType(Nette\Application\Application::class);
$app->run();

<?php

use Opdavies\GmailFilterBuilder\Console\Command\GenerateCommand;
use Opdavies\GmailFilterBuilder\Container\Container;
use Symfony\Component\Console\Application;

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require_once __DIR__.'/../vendor/autoload.php';
}
elseif (file_exists(__DIR__.'/../../../autoload.php')) {
    require_once __DIR__.'/../../../autoload.php';
}

$container = new Container();

/** @var Application $application */
$application = $container->get('app.cli');
$application->setDefaultCommand(GenerateCommand::NAME);
$application->run();

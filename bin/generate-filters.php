<?php

use Opdavies\GmailFilterBuilder\Console\Command\GenerateCommand;
use Symfony\Component\Console\Application;

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require_once __DIR__.'/../vendor/autoload.php';
} elseif (file_exists(__DIR__.'/../../../autoload.php')) {
    require_once __DIR__.'/../../../autoload.php';
}

$application = new Application();
$application->add(new GenerateCommand());
$application->setDefaultCommand(GenerateCommand::getDefaultName());
$application->run();

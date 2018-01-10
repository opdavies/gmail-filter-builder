<?php

use Opdavies\GmailFilterBuilder\Container\Container;
use Symfony\Component\Console\Application;

require_once __DIR__.'/../vendor/autoload.php';

$container = new Container();

/** @var Application $application */
$application = $container->get('app.cli');
$application->run();

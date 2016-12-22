#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use YTBundle\Command\GreetCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new GreetCommand());
$application->run();
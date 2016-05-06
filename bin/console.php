#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/../vendor/autoload.php';

use Carnage\Selenium\Command\RunTestsCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new RunTestsCommand());
$application->run();
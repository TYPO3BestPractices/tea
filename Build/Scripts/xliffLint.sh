#!/usr/bin/env php
<?php

include_once __DIR__ . '/../../.Build/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Translation\Command\XliffLintCommand;
use Symfony\Component\Yaml\Command\LintCommand;

$application = new Application();
$application->add(new XliffLintCommand(null, null, null, false));
$application->add(new LintCommand());

exit($application->run());

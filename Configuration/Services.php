<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use TTN\Tea\Command\CreateTestDataCommand;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();

    $services
        ->load('TTN\\Tea\\', '../Classes/*')
        ->exclude('../Classes/Domain/Model/*');

    $services
        ->set(CreateTestDataCommand::class)
        ->tag(
            'console.command',
            [
                'command' => 'tea:create-test-data',
                'description' => 'Create test data in existing sysfolder',
            ],
        );
};

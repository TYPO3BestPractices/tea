<?php

declare(strict_types=1);

namespace TTN\Tea\Upgrades;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;

#[UpgradeWizard('tea_listTypeToCTypeUpdate')]
final class ListTypeToCTypeUpdate extends AbstractListTypeToCTypeUpdate
{
    public function getListTypeToCTypeMapping(): array
    {
        return [
            'tea_teaindex' => 'tea_teaindex',
            'tea_teashow' => 'tea_teashow',
            'tea_teafront_end_editor' => 'tea_teafront_end_editor',
        ];
    }

    public function getTitle(): string
    {
        return 'Migrates tea extension plugins';
    }

    public function getDescription(): string
    {
        return 'Migrates tea_index, tea_show, tea_front_end_editor from list_type to CType.';
    }
}

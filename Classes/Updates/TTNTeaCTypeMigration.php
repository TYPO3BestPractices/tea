<?php

declare(strict_types=1);

namespace TTN\Tea\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('ttnTeaCTypeMigration')]
final class TTNTeaCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "TTN Tea" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "TTN Tea" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'tea_teaindex' => 'tea_teaindex',
            'tea_teashow' => 'tea_teashow',
            'tea_teafrontendeditor' => 'tea_teafrontendeditor',
        ];
    }
}

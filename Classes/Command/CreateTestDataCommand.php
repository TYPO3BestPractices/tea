<?php

declare(strict_types=1);

namespace TTN\Tea\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\ReferenceIndex;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command to create test data for the tea extension.
 */
final class CreateTestDataCommand extends Command
{
    /**
     * @var list<array{title: non-empty-string, description: non-empty-string, sys_language_uid: int<0, max>}>
     */
    protected array $teaData = [
        [
            'title' => 'Darjeeling',
            'description' => 'I love that tea!',
            'sys_language_uid' => 0,
        ],
        [
            'title' => 'Earl Grey',
            'description' => 'A nice tea!',
            'sys_language_uid' => 0,
        ],
    ];

    protected function configure(): void
    {
        $this
            ->setHelp('Create test data for the tea extension in an already existing page (sysfolder).')
            ->addArgument(
                'pageUid',
                InputArgument::REQUIRED,
                'Existing sysfolder page id.'
            )
            ->addOption(
                'delete-data-before',
                'd',
                InputOption::VALUE_NONE,
                'Delete all tea data in the defined pid before creating new data.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pageUid = $input->getArgument('pageUid') ?? 0;
        \assert(\is_int($pageUid));
        $deleteDataBefore = $input->getOption('delete-data-before') ?? false;
        \assert(\is_bool($deleteDataBefore));
        $table = 'tx_tea_domain_model_tea';
        $connectionForTable = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);

        if ($deleteDataBefore) {
            $query = $connectionForTable;
            $query->delete($table, ['pid' => $pageUid], [Connection::PARAM_INT]);
            $output->writeln(sprintf('Existing data in page %s deleted.', $pageUid));
        }

        $query = $connectionForTable;
        foreach ($this->teaData as $item) {
            $item = ['pid' => $pageUid, 'title' => $item['title'], 'description' => $item['description']];
            $query->insert($table, $item);
        }
        $output->writeln(\sprintf('Test data in page %s created.', $pageUid));

        $referenceIndex = GeneralUtility::makeInstance(ReferenceIndex::class);
        $referenceIndex->updateIndex(false);
        $output->writeln('Reference index updated.');

        return Command::SUCCESS;
    }
}

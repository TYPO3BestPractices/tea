<?php

declare(strict_types=1);

namespace TTN\Tea\Domain\Repository;

use TTN\Tea\Domain\Model\Tea;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Tea>
 */
class TeaRepository extends Repository
{
    protected $defaultOrderings = ['title' => QueryInterface::ORDER_ASCENDING];

    /**
     * @param positive-int $ownerUid
     *
     * @return QueryResultInterface<Tea>
     */
    public function findByOwnerUid(int $ownerUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setQuerySettings($query->getQuerySettings()->setRespectStoragePage(false));
        $query->matching($query->equals('ownerUid', $ownerUid));

        return $query->execute();
    }
}

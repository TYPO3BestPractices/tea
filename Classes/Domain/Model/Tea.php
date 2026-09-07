<?php

declare(strict_types=1);

namespace TTN\Tea\Domain\Model;

use TYPO3\CMS\Extbase\Attribute\ORM\Lazy;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * This class represents a tea (flavor), e.g., "Earl Grey".
 */
class Tea extends AbstractEntity
{
    #[Validate(validator: 'StringLength', options: ['maximum' => 255])]
    #[Validate(validator: 'NotEmpty')]
    protected string $title = '';

    #[Validate(validator: 'StringLength', options: ['maximum' => 2000])]
    protected string $description = '';

    #[Lazy]
    protected FileReference|LazyLoadingProxy|null $image = null;

    /**
     * @var int<0, max>
     */
    protected int $ownerUid = 0;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): ?FileReference
    {
        if ($this->image instanceof LazyLoadingProxy) {
            $image = $this->image->_loadRealInstance();
            $this->image = ($image instanceof FileReference) ? $image : null;
        }

        return $this->image;
    }

    public function setImage(FileReference $image): void
    {
        $this->image = $image;
    }

    /**
     * @return int<0, max>
     */
    public function getOwnerUid(): int
    {
        return $this->ownerUid;
    }

    /**
     * @param int<0, max> $ownerUid
     */
    public function setOwnerUid(int $ownerUid): void
    {
        $this->ownerUid = $ownerUid;
    }
}

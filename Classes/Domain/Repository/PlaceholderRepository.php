<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Domain\Repository;

class PlaceholderRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function getAllPlaceholder(int $language = 0)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setLanguageUid($language);

        return $query->execute();
    }
}

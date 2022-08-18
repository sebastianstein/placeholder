<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SebastianStein\Placeholder\Domain\Model\Placeholder;
use SebastianStein\Placeholder\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PlaceholderService implements SingletonInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected $placeholder = [];

    protected $language = 0;

    public function __construct()
    {
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $this->language = $languageAspect->getId();
        $this->setPlaceholder();
    }

    public function replacePlaceholder(string &$target): void
    {
        preg_match_all('/[#]{3}[A-Z0-9\-+]*[#]{3}/', $target, $matches);

        if (!empty($matches)) {
            $matches = array_unique($matches[0]);

            foreach ($matches as $match) {
                if (array_key_exists($match, $this->placeholder[$this->language])) {
                    $target = str_replace($match, $this->placeholder[$this->language][$match]['value'], $target);
                }
            }
        }
    }

    protected function setPlaceholder(): void
    {
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Placeholder::TABLE);
        $placeholderRecords = $queryBuilder->select('*')->from(Placeholder::TABLE)->execute()->fetchAllAssociative();

        foreach ($placeholderRecords as $placeholder) {
            $this->placeholder[$placeholder['sys_language_uid']][$placeholder['marker_identifier']] = $placeholder;
        }
    }
}

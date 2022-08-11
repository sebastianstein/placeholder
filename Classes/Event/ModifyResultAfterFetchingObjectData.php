<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Event;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SebastianStein\Placeholder\Service\PlaceholderService;
use SebastianStein\Placeholder\Utility\PlaceholderConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Persistence\ModifyResultAfterFetchingObjectDataEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\Selector;

class ModifyResultAfterFetchingObjectData implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var PlaceholderConfigurationUtility
     */
    protected $placeholderConfigurationUtility;

    /**
     * @var PlaceholderService
     */
    protected $placeholderService;

    public function __construct()
    {
        $this->placeholderConfigurationUtility =
            GeneralUtility::makeInstance(PlaceholderConfigurationUtility::class);
        $this->placeholderService = GeneralUtility::makeInstance(PlaceholderService::class);
    }

    public function replacePlaceholder(ModifyResultAfterFetchingObjectDataEvent $event): void
    {
        $result = $event->getResult();
        if ($event->getQuery()->getSource() instanceof Selector) {
            $tableName = $event->getQuery()->getSource()->getSelectorName();

            if ($tableName === 'tt_content') {
                // tt_content elements are handled with the SebastianStein\Placeholder\DataProcessing\PlaceholderProcessor
                return;
            }

            if ($this->placeholderConfigurationUtility->existPlaceholderFieldConfigurationKey($tableName)) {
                $placeholderFields =
                    $this->placeholderConfigurationUtility->getPlaceholderFieldConfigurationByKey($tableName);

                foreach ($result as $resultKey => $item) {
                    foreach ($placeholderFields as $propertyName) {
                        if (array_key_exists($propertyName, $item) && !empty($item[$propertyName])) {
                            $this->placeholderService->replacePlaceholder($item[$propertyName]);
                            $result[$resultKey] = $item;
                        }
                    }
                }

                $event->setResult($result);
            }
        }
    }
}

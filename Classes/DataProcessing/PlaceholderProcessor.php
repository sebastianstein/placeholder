<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\DataProcessing;

use SebastianStein\Placeholder\Service\PlaceholderService;
use SebastianStein\Placeholder\Utility\PlaceholderConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class PlaceholderProcessor implements DataProcessorInterface
{
    /**
     * @var PlaceholderService
     */
    protected $placeholderService;

    /**
     * @var PlaceholderConfigurationUtility
     */
    protected $placeholderConfigurationUtility;

    public function __construct()
    {
        $this->placeholderService = GeneralUtility::makeInstance(PlaceholderService::class);
        $this->placeholderConfigurationUtility = GeneralUtility::makeInstance(PlaceholderConfigurationUtility::class);
    }

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $placeholderConfiguration = $this->placeholderConfigurationUtility->getPlaceholderFieldConfiguration();

        if (array_key_exists($cObj->getCurrentTable(), $placeholderConfiguration)) {
            $tableConfiguration = $placeholderConfiguration[$cObj->getCurrentTable()];

            if (is_array($tableConfiguration)) {
                $currentCType = $processedData['data']['CType'];

                // by TCA type
                if (array_key_exists($currentCType, $tableConfiguration)) {
                    foreach ($tableConfiguration[$currentCType] as $fieldName) {
                        $this->replaceIfFieldExist($fieldName, $processedData);
                    }
                } else {
                    // by table
                    foreach ($tableConfiguration as $fieldName) {
                        $this->replaceIfFieldExist($fieldName, $processedData);
                    }
                }
            }
        }

        return $processedData;
    }

    private function replaceIfFieldExist(string $field, array &$processedData)
    {
        if (array_key_exists($field, $processedData['data']) &&
            !empty($processedData['data'][$field])) {
            $this->placeholderService->replacePlaceholder($processedData['data'][$field]);
        }
    }
}

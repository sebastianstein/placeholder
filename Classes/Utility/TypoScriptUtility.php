<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Utility;

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class TypoScriptUtility
{
    /**
     * @return array
     */
    public static function getFullTypoScript(): array
    {
        $typoScript =
            GeneralUtility::makeInstance(ConfigurationManager::class)->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );

        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        return $typoScriptService->convertTypoScriptArrayToPlainArray($typoScript);
    }
}

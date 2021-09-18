<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'placeholder',
    'Configuration/TypoScript',
    'Placeholder TypoScript configuration'
);

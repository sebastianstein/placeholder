<?php

if (!defined('TYPO3_MODE')) {
    die();
}

call_user_func(
    function () {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1612391583] = [
            'nodeName' => 'placeholder',
            'priority' => 40,
            'class' => \SebastianStein\Placeholder\Form\Element\PlaceholderElement::class,
        ];

        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        $iconRegistry->registerIcon(
            'actions-eye-hide',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:placeholder/Resources/Public/Images/Icons/hide.svg']
        );

        // @todo if use ckeditor plugin
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] =
            'EXT:placeholder/Configuration/Yaml/CkEditor/Default.yaml';

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['placeholder']['configuration'] =
            'EXT:placeholder/Configuration/Yaml/Placeholder/Default.yaml';
    }
);

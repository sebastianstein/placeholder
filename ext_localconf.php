<?php

if (!defined('TYPO3_MODE')) {
    die();
}

call_user_func(
    function () {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1612391583] = [
            'nodeName' => 'placeholderInput',
            'priority' => 40,
            'class' => \SebastianStein\Placeholder\FormEngine\Element\PlaceholderInputElement::class,
        ];
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1612391584] = [
//            'nodeName' => 'placeholderText',
//            'priority' => 40,
//            'class' => \SebastianStein\Placeholder\FormEngine\Element\PlaceholderTextElement::class,
//        ];

        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        $iconRegistry->registerIcon(
            'actions-eye-hide',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:placeholder/Resources/Public/Images/Icons/hide.svg']
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\SebastianStein\Placeholder\FormEngine\Evaluation\UniqueMarker::class] =
            '';

        // @todo feature switch
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['placeholder'] =
            'EXT:placeholder/Configuration/Yaml/CkEditor/Placeholder.yaml';
    }
);

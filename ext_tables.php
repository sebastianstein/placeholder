<?php

call_user_func(
    function () {
        defined('TYPO3_MODE') || die();

        $GLOBALS['TBE_STYLES']['skins']['placeholder']['stylesheetDirectories'][] = 'EXT:placeholder/Resources/Public/Css/Backend/';

    }
);

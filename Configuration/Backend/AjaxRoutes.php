<?php

return [

    'placeholder_get_all_placeholder' => [
        'path' => '/placeholder/get/all',
        'target' => \SebastianStein\Placeholder\Controller\PlaceholderController::class . '::ajaxGetAllPlaceholder'
    ],
    'placeholder_exist_marker' => [
        'path' => '/placeholder/marker/exist',
        'target' => \SebastianStein\Placeholder\Controller\PlaceholderController::class . '::ajaxExistPlaceholder'
    ],

];

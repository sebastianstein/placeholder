<?php

$ll = 'LLL:EXT:placeholder/Resources/Private/Language/Backend/locallang.xlf:';

return [
    'ctrl' => [
        'title' => $ll . \SebastianStein\Placeholder\Domain\Model\Placeholder::TABLE,
        'label' => 'value',
        'label_alt' => 'marker_identifier',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'default_sortby' => 'ORDER BY tstamp ASC',
        'searchFields'  => 'marker_identifier, description, value',
        'delete' => 'deleted',
        'iconfile' => 'EXT:placeholder/Resources/Public/Images/Icons/placeholder.svg',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
    ],
    'interface' => [
        'showRecordFieldList' => '',
    ],
    'types' => [
        '0' => [
            'showitem' => 'marker_identifier, description, value'
        ],
    ],
    'columns' => [
        'marker_identifier' => [
            'exclude' => true,
            'label' => $ll . 'TCA.placeholder.marker_identifier',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => SebastianStein\Placeholder\FormEngine\Evaluation\UniqueMarker::class . ', trim ,required',
                'max' => 255,
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => $ll . 'TCA.placeholder.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim,required',
            ],
        ],
        'value' => [
            'exclude' => true,
            'label' => $ll . 'TCA.placeholder.value',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
    ]
];

<?php

$ll = 'LLL:EXT:placeholder/Resources/Private/Language/backend.xlf:';

return [
    'ctrl' => [
        'title' => $ll . \SebastianStein\Placeholder\Domain\Model\Placeholder::TABLE,
        'label' => 'marker_identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'default_sortby' => 'ORDER BY tstamp ASC',
        'delete' => 'deleted',
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
                'eval' => 'trim,required',
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

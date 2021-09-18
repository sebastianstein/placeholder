<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class PlaceholderElement extends AbstractFormElement
{
    public function render()
    {
        $row = $this->data['databaseRow'];
        $parameterArray = $this->data['parameterArray'];
        $color = $parameterArray['fieldConf']['config']['parameters']['color'];
        $size = $parameterArray['fieldConf']['config']['parameters']['size'];

        $fieldInformationResult = $this->renderFieldInformation();
        $fieldInformationHtml = $fieldInformationResult['html'];
        $resultArray =
            $this->mergeChildReturnIntoExistingResult($this->initializeResultArray(), $fieldInformationResult, false);

        $fieldId = StringUtility::getUniqueId('formengine-textarea-');

        $attributes = [
            'id' => $fieldId,
            'name' => htmlspecialchars($parameterArray['itemFormElName']),
            'size' => $size,
            'data-formengine-input-name' => htmlspecialchars($parameterArray['itemFormElName']),
            'onChange' => implode('', $parameterArray['fieldChangeFunc']),

        ];

        $classes = [
            'form-control',
            't3js-formengine-textarea',
            'formengine-textarea',

        ];
        $itemValue = $parameterArray['itemFormElValue'];
        $attributes['class'] = implode(' ', $classes);

        $html = [];
        $html[] =
            '<div class=”formengine-field-item t3js-formengine-field-item” style=”padding: 5px; background-color: ' . $color . ';”>';
        $html[] = $fieldInformationHtml;
        $html[] = '<div class="form-wizards-wrap">';
        $html[] = '<div class="form-wizards-element">';
        $html[] = '<div class="form-control-wrap">';
        $html[] = '<div class="input-group" data-placeholder-field-id="' . $fieldId . '" data-placeholder-record-language="'.$row['sys_language_uid'][0].'">';
        $html[] = '<div class="placeholder-group">';
        $html[] = '<div class="placeholder-overlay"></div>';
        $html[] = '<div class="placeholder-editable"></div>';
        $html[] = '</div>';
        $html[] =
            '<input type="hidden" data-placeholder-input value="' . htmlspecialchars(
                $itemValue,
                ENT_QUOTES
            ) . '" ';
        $html[] = GeneralUtility::implodeAttributes($attributes, true);
        $html[] = ' />';

        $html[] = '<span class="input-group-btn">';
        $html[] = '<button class="btn btn-default" type="button" data-placeholder-show title="' . LocalizationUtility::translate('LLL:EXT:placeholder/Resources/Private/Language/Backend/locallang.xlf:show-placeholder') . '">';
        $html[] =                          $this->iconFactory->getIcon('actions-eye', Icon::SIZE_SMALL)->render();
        $html[] = '</button>';
        $html[] = '<button class=" btn btn-default active hide" data-placeholder-hide type="button" title="' . LocalizationUtility::translate('LLL:EXT:placeholder/Resources/Private/Language/Backend/locallang.xlf:hide-placeholder') . '">';
        $html[] =                          $this->iconFactory->getIcon('actions-eye-hide', Icon::SIZE_SMALL)->render();
        $html[] = '</button>';
        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</div>';
        $resultArray['html'] = implode(LF, $html);

        $resultArray['requireJsModules'][] =
            ['TYPO3/CMS/Placeholder/FormEngine/Element/PlaceholderElement' => ''];

        return $resultArray;
    }
}

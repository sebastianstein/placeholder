<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\FormEngine\Element;

use TYPO3\CMS\Backend\Form\Element\InputTextElement;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class PlaceholderInputElement extends InputTextElement
{
    public function render()
    {
        $fieldName = $this->data['fieldName'];
        $row = $this->data['databaseRow'];
        $parameterArray = $this->data['parameterArray'];
        $resultArray = $this->initializeResultArray();

        $itemValue = $parameterArray['itemFormElValue'];
        $config = $parameterArray['fieldConf']['config'];

        $evalList = GeneralUtility::trimExplode(',', $config['eval'], true);
        $size =
            MathUtility::forceIntegerInRange(
                $config['size'] ?? $this->defaultInputWidth,
                $this->minimumInputWidth,
                $this->maxInputWidth
            );
        $width = (int)$this->formMaxWidth($size);

        $fieldInformationResult = $this->renderFieldInformation();
        $fieldInformationHtml = $fieldInformationResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldInformationResult, false);

        // @todo: The whole eval handling is a mess and needs refactoring
        foreach ($evalList as $func) {
            // @todo: This is ugly: The code should find out on it's own whether an eval definition is a
            // @todo: keyword like "date", or a class reference. The global registration could be dropped then
            // Pair hook to the one in \TYPO3\CMS\Core\DataHandling\DataHandler::checkValue_input_Eval()
            if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][$func])) {
                if (class_exists($func)) {
                    $evalObj = GeneralUtility::makeInstance($func);
                    if (method_exists($evalObj, 'deevaluateFieldValue')) {
                        $_params = [
                            'value' => $itemValue
                        ];
                        $itemValue = $evalObj->deevaluateFieldValue($_params);
                    }
                    if (method_exists($evalObj, 'returnFieldJS')) {
                        $resultArray['additionalJavaScriptPost'][] =
                            'TBE_EDITOR.customEvalFunctions[' . GeneralUtility::quoteJSvalue($func) . ']'
                            . ' = function(value) {' . $evalObj->returnFieldJS() . '};';
                    }
                }
            }
        }

        $fieldId = StringUtility::getUniqueId('formengine-input-');

        $attributes = [
            'value' => $parameterArray['itemFormElValue'],
            'id' => $fieldId,
            'class' => implode(' ', [
                'form-control',
                'hasDefaultValue',
            ]),
            'data-formengine-validation-rules' => $this->getValidationDataAsJsonString($config),
            'data-formengine-input-params' => (string)json_encode([
                                                                      'field' => $parameterArray['itemFormElName'],
                                                                      'evalList' => implode(',', $evalList),
                                                                      'is_in' => trim($config['is_in'] ?? '')
                                                                  ]),
            'data-formengine-input-name' => (string)$parameterArray['itemFormElName'],
        ];

        $maxLength = $config['max'] ?? 0;
        if ((int)$maxLength > 0) {
            $attributes['maxlength'] = (string)(int)$maxLength;
        }
        if (!empty($config['placeholder'])) {
            $attributes['placeholder'] = trim($config['placeholder']);
        }
        if (isset($config['autocomplete'])) {
            $attributes['autocomplete'] = empty($config['autocomplete']) ? 'new-' . $fieldName : 'on';
        }

        $fieldControlResult = $this->renderFieldControl();
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldControlResult, false);

        $fieldWizardResult = $this->renderFieldWizard();
        $fieldWizardHtml = $fieldWizardResult['html'];

        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldWizardResult, false);
        $inputType = 'hidden';

        $mainFieldHtml = [];
        $mainFieldHtml[] = '<div class="form-control-wrap" style="max-width: ' . $width . 'px">';
        $mainFieldHtml[] = '<div class="form-wizards-wrap">';
        $mainFieldHtml[] = '<div class="form-wizards-element">';
        $mainFieldHtml[] =
            '<div class="input-group" data-placeholder-field-id="' . $fieldId . '" data-placeholder-record-language="' . $row['sys_language_uid'][0] . '">';
        $mainFieldHtml[] = '<div class="placeholder-group">';
        $mainFieldHtml[] = '<div class="placeholder-overlay"></div>';
        $mainFieldHtml[] = '<div class="placeholder-editable"></div>';
        $mainFieldHtml[] = '</div>';
        $mainFieldHtml[] =
            '<input type="' . $inputType . '" data-placeholder-input ' . GeneralUtility::implodeAttributes(
                $attributes,
                true
            ) . ' />';
        $mainFieldHtml[] = '<span class="input-group-btn">';
        $mainFieldHtml[] =
            '<button class="btn btn-default" type="button" data-placeholder-show title="Show placeholder content">';
        $mainFieldHtml[] = $this->iconFactory->getIcon('actions-eye', Icon::SIZE_SMALL)->render();
        $mainFieldHtml[] = '</button>';
        $mainFieldHtml[] =
            '<button class=" btn btn-default active hide" data-placeholder-hide type="button" title="Show placeholder">';
        $mainFieldHtml[] = $this->iconFactory->getIcon('actions-eye-hide', Icon::SIZE_SMALL)->render();
        $mainFieldHtml[] = '</button>';
        $mainFieldHtml[] = '</span>';
        $mainFieldHtml[] = '</div>';
        $mainFieldHtml[] = '</div>';
        if (!empty($fieldWizardHtml)) {
            $mainFieldHtml[] = '<div class="form-wizards-items-bottom">';
            $mainFieldHtml[] = $fieldWizardHtml;
            $mainFieldHtml[] = '</div>';
        }
        $mainFieldHtml[] = '</div>';
        $mainFieldHtml[] = '</div>';
        $mainFieldHtml = implode(LF, $mainFieldHtml);

        $fullElement = $mainFieldHtml;

        $resultArray['html'] =
            '<div class="formengine-field-item t3js-formengine-field-item">' . $fieldInformationHtml . $fullElement . '</div>';
        $resultArray['requireJsModules'][] =
            ['TYPO3/CMS/Placeholder/Backend/FormEngine/Element/PlaceholderElement' => ''];

        return $resultArray;
    }
}

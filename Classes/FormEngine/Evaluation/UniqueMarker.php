<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\FormEngine\Evaluation;

use SebastianStein\Placeholder\Service\MarkerValidationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UniqueMarker
{
    /**
     * @var MarkerValidationService
     */
    protected $markerValidationService;

    public function __construct()
    {
        $this->markerValidationService = GeneralUtility::makeInstance(MarkerValidationService::class);
    }

    /**
     * This function just return the field value as it is. No transforming,
     * hashing will be done on server-side.
     *
     * @return string JavaScript code for evaluating the
     */
    public function returnFieldJS(): string
    {
        return 'return value;';
    }

    /**
     * Unique check for Markers
     *
     * @param string $value
     * @param string $is_in
     * @param bool $set
     * @return mixed
     */
    public function evaluateFieldValue($value, string $is_in, int &$set): string
    {
        $this->markerValidationService->validateMarker($value);

        return $value;
    }


}

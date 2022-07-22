<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Hook;

use SebastianStein\Placeholder\Domain\Model\Placeholder;
use SebastianStein\Placeholder\Domain\Repository\PlaceholderRepository;
use SebastianStein\Placeholder\Service\MarkerValidationService;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class TceMainHook
{

    /**
     * @var MarkerValidationService
     */
    protected $markerValidationService;

    /**
     * @var PlaceholderRepository
     */
    protected $placeholderRepository;

    public function __construct(
        MarkerValidationService $markerValidationService,
        PlaceholderRepository $placeholderRepository
    ) {
        $this->markerValidationService = $markerValidationService;
        $this->placeholderRepository = $placeholderRepository;
    }

    /**
     * @param string $status
     * @param string $table
     * @param string $id
     * @param array $fieldArray
     * @param DataHandler $dataHandler
     *
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    public function processDatamap_postProcessFieldArray(
        string $status,
        string $table,
        string $id,
        array &$fieldArray,
        DataHandler $dataHandler
    ): void {
        if ($status === 'new' &&
            $table === Placeholder::TABLE &&
            !$this->markerValidationService->validateMarker($fieldArray['marker'])
        ) {
            unset($fieldArray['pid']);
        }

        if ($status === 'update' &&
            $table === Placeholder::TABLE &&
            array_key_exists('marker', $fieldArray) &&
            !$this->markerValidationService->isMarkerSyntaxValid($fieldArray['marker'])
        ) {
            $placeholder = $this->placeholderRepository->findByUid($id);
            if(!is_null($placeholder)) {
                $fieldArray['marker'] = $placeholder->getMarker();
            }

        }
    }
}

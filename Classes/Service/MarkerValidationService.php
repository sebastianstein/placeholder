<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Service;

use SebastianStein\Placeholder\Domain\Repository\PlaceholderRepository;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MarkerValidationService implements SingletonInterface
{
    /**
     * Placeholder Repository
     *
     * @var PlaceholderRepository $placeholderRepository
     */
    protected $placeholderRepository;

    /**
     * @var FlashMessageService
     */
    protected $flashMessageService;

    /**
     * @var FlashMessageQueue
     */
    protected $flashMessageQueue;

    /**
     * @var string
     */
    protected $languageFile = 'LLL:EXT:placeholder/Resources/Private/Language/Backend/locallang.xlf:';

    /**
     * Constructor
     *
     * @param PlaceholderRepository $placeholderRepository
     * @param FlashMessageService $flashMessageService
     */
    public function __construct(PlaceholderRepository $placeholderRepository, FlashMessageService $flashMessageService)
    {
        $this->placeholderRepository = $placeholderRepository;
        $this->flashMessageService = $flashMessageService;

        $this->flashMessageQueue = $this->flashMessageService->getMessageQueueByIdentifier();
    }

    public function validateMarker(string $marker): bool
    {
        return $this->isMarkerSyntaxValid($marker) && $this->isMarkerIdentifierUnique($marker);
    }

    /**
     * Checks the marker for correct syntax
     *
     * @param $marker string
     * @return bool
     */
    public function isMarkerSyntaxValid(string $marker): bool
    {
        return $this->isNameValid($marker) && $this->isSyntaxValid($marker);
    }

    private function isNameValid(string $marker): bool
    {
        $markerString = substr(substr($marker, 3), 0, -3);

        if (!preg_match('/^[A-Z0-9\-]+$/', $markerString)) {
            $this->addFlashMessageToQueue(
                LocalizationUtility::translate($this->languageFile . 'error.marker.invalidName.title'),
                LocalizationUtility::translate($this->languageFile . 'error.marker.invalidName.message')
            );

            return false;
        }

        return true;
    }

    private function isSyntaxValid(string $marker): bool
    {
        if (strpos($marker, '###') !== 0 || substr($marker, -3) !== '###') {
            $this->addFlashMessageToQueue(
                LocalizationUtility::translate($this->languageFile . 'error.marker.invalidSyntax.title'),
                LocalizationUtility::translate($this->languageFile . 'error.marker.invalidSyntax.message')
            );

            return false;
        }

        return true;
    }

    private function isMarkerIdentifierUnique(string $marker): bool
    {
        if (count($this->placeholderRepository->findByMarkerIdentifier($marker)) > 0) {
            $this->addFlashMessageToQueue(
                LocalizationUtility::translate($this->languageFile . 'error.marker.identifierNotUnique.title'),
                LocalizationUtility::translate($this->languageFile . 'error.marker.identifierNotUnique.message')
            );

            return false;
        }

        return true;
    }

    private function addFlashMessageToQueue(
        string $title,
        string $message
    ): void {
        $this->flashMessageQueue->addMessage(
            GeneralUtility::makeInstance(
                FlashMessage::class,
                $message,
                $title,
                AbstractMessage::ERROR,
                true
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianStein\Placeholder\Domain\Model\Placeholder;
use SebastianStein\Placeholder\Domain\Repository\PlaceholderRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PlaceholderController
{
    /**
     * @var PlaceholderRepository
     */
    private $placeholderRepository;

    public function __construct()
    {
        $this->placeholderRepository = GeneralUtility::makeInstance(PlaceholderRepository::class);
    }

    public function ajaxExistPlaceholder(ServerRequestInterface $request): ResponseInterface
    {
        $result = [true];
        return new JsonResponse($result);
    }

    public function ajaxGetAllPlaceholder(ServerRequestInterface $request): ResponseInterface
    {
        $response = [];
        $arguments = $request->getParsedBody();
        $language = (int)$arguments['language'];

        $allPlaceholder = $this->placeholderRepository->getAllPlaceholder($language);

        /** @var Placeholder $placeholder */
        foreach ($allPlaceholder as $placeholder) {
            $response[$placeholder->getMarkerIdentifier()] = $placeholder->toArray();
        }

        return new JsonResponse($response);
    }
}

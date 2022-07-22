<?php

declare(strict_types=1);

namespace SebastianStein\Placeholder\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SebastianStein\Placeholder\Domain\Repository\PlaceholderRepository;
use TYPO3\CMS\Core\Http\JsonResponse;

class PlaceholderController
{
    /**
     * @var PlaceholderRepository
     */
    private $placeholderRepository;

    public function __construct(PlaceholderRepository $placeholderRepository)
    {
        $this->placeholderRepository = $placeholderRepository;
    }

    public function ajaxGetAllPlaceholder(ServerRequestInterface $request): ResponseInterface
    {
        $response = [];
        $arguments = $request->getParsedBody();
        $language = (int)$arguments['language'];

        $allPlaceholder = $this->placeholderRepository->getAllPlaceholder($language);

        foreach ($allPlaceholder as $placeholder) {
            $response[$placeholder->getMarkerIdentifier()] = $placeholder->toArray();
        }

        return new JsonResponse($response);
    }
}

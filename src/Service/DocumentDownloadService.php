<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Document;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\MimeTypes;

final readonly class DocumentDownloadService
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function download(Document $document): BinaryFileResponse
    {

        $filename = $document->getFilename();
        $path = $document->getPath();
        /** @var string|null $rootPath */
        $rootPath = $this->parameterBag->get('kernel.project_dir');

        if (null === $filename || null === $path || null === $rootPath) {
            throw new \InvalidArgumentException('Document filename or path not found');
        }

        $file = $rootPath.$path.$filename;

        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->guessMimeType($file);

        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Type', $mimeType);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Admin\Document;

use App\Entity\Document;
use App\Service\DocumentDownloadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DownloadDocumentController extends AbstractController
{
    public function __construct(private readonly DocumentDownloadService $documentDownloadService)
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/document/{id}/download', name: 'app_document_admin_download', methods: ['GET'])]
    public function __invoke(Document $document): BinaryFileResponse
    {
        return $this->documentDownloadService->download($document);
    }
}

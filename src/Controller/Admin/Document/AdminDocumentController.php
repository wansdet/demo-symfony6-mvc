<?php

declare(strict_types=1);

namespace App\Controller\Admin\Document;

use App\Entity\Document;
use App\Entity\User;
use App\Service\DocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/document')]
final class AdminDocumentController extends AbstractController
{
    public function __construct(private readonly DocumentService $documentService)
    {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'app_document_admin_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = 10;
        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->documentService->findByUser($page, $perPage, $user);

        return $this->render('document/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'app_document_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('delete'.$document->getId(), $token)) {
            $this->documentService->delete($document);
            $this->addFlash('success', 'Document deleted.');
        } else {
            $this->addFlash('danger', 'Document cannot be deleted. Please contact the administrator.');

            return $this->redirectToRoute('app_document_admin_index');
        }

        return $this->redirectToRoute('app_document_admin_index');
    }
}

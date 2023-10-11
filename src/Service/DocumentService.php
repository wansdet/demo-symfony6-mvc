<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Document;
use App\Entity\User;
use App\Repository\DocumentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class DocumentService
{
    public function __construct(
        private DocumentRepository $documentRepository,
        private PaginatorInterface $paginator,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function delete(Document $document): void
    {
        // Delete the document from the filesystem
        /** @var string|null $rootPath */
        $rootPath = $this->parameterBag->get('kernel.project_dir');
        $file = $rootPath.$document->getPath().$document->getFilename();
        unlink($file);

        // Delete the document from the database
        $this->documentRepository->delete($document);
    }

    /**
     * @return PaginationInterface<Document>
     */
    public function findByUser(int $page, int $perPage, User $user): PaginationInterface
    {
        return $this->findByQuery($page, $perPage, ['user' => $user], ['createdAt' => 'DESC']);
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return PaginationInterface<Document>
     */
    private function findByQuery(int $page, int $perPage, array $criteria = [], array $orderBy = null): PaginationInterface
    {
        $queryBuilder = $this->documentRepository->findByQuery($criteria, $orderBy, $perPage, ($page - 1) * $perPage);

        // Paginate the query
        return $this->paginator->paginate(
            $queryBuilder,  // Query to paginate
            $page,          // Current page number
            $perPage        // Number of items per page
        );
    }
}

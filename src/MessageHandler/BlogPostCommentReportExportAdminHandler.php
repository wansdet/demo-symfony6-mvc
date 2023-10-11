<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\BlogPostCommentReportExportAdmin;
use App\Message\DocumentReadyNotification;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class BlogPostCommentReportExportAdminHandler
{
    use BlogPostCommentReportExportTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag,
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(BlogPostCommentReportExportAdmin $blogPostCommentReportExportAdmin): void
    {
        $criteria = [];
        $this->generateBlogPostCommentReport(
            $blogPostCommentReportExportAdmin->getUserId(),
            $criteria,
            $this->parameterBag,
            $this->entityManager,
        );

        $subject = 'Blog Post Comment Report';
        $content = 'Your blog post comment report is ready for download.';
        $this->bus->dispatch(new DocumentReadyNotification($blogPostCommentReportExportAdmin->getUserId(), $subject, $content));
    }
}

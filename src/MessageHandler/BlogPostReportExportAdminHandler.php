<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\BlogPostReportExportAdmin;
use App\Message\DocumentReadyNotification;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class BlogPostReportExportAdminHandler
{
    use BlogPostReportExportTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag,
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(BlogPostReportExportAdmin $blogPostReportExportAdmin): void
    {
        $criteria = [];
        $this->generateBlogPostReport(
            $blogPostReportExportAdmin->getUserId(),
            $criteria,
            $this->parameterBag,
            $this->entityManager,
        );

        $subject = 'Blog Post Report';
        $content = 'Your blog post report is ready for download.';
        $this->bus->dispatch(new DocumentReadyNotification($blogPostReportExportAdmin->getUserId(), $subject, $content));
    }
}

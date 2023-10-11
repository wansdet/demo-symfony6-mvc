<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\BlogPostComment;
use App\Entity\Document;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

trait BlogPostCommentReportExportTrait
{
    /**
     * @param array<string, mixed> $criteria
     *
     * @throws Exception
     */
    public function generateBlogPostCommentReport(
        int $userId,
        array $criteria,
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $entityManager
    ): void {
        /** @var string|null $rootPath */
        $rootPath = $parameterBag->get('kernel.project_dir');
        $templateFilePath = $rootPath.'/storage/templates/blog_post_comment_report.xlsx';
        $path = '/storage/documents/users/'.$userId.'/';
        $documentPath = $rootPath.$path;
        if (!file_exists($documentPath)) {
            mkdir($documentPath, 0755, true);
        }

        $orderBy = ['createdAt' => 'DESC'];
        $comments = $entityManager->getRepository(BlogPostComment::class)->findBy($criteria, $orderBy);

        $inputFileName = $templateFilePath;
        $spreadsheet = IOFactory::load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();

        $row = 2;
        foreach ($comments as $comment) {
            $sheet->setCellValue('A'.$row, $comment->getId());
            $sheet->setCellValue('B'.$row, $comment->getContent());
            $blogPost = $comment->getBlogPost();
            if (null !== $blogPost) {
                $sheet->setCellValue('C'.$row, $blogPost->getTitle());
                $sheet->setCellValue('D'.$row, $blogPost->getSlug());
            } else {
                $sheet->setCellValue('C'.$row, 'N/A');
                $sheet->setCellValue('D'.$row, 'N/A');
            }
            $author = $comment->getAuthor();
            if (null !== $author) {
                $sheet->setCellValue('E'.$row, $author->getFullName());
            } else {
                $sheet->setCellValue('E'.$row, 'N/A');
            }
            $sheet->setCellValue('F'.$row, $comment->getStatus());
            $createdAt = $comment->getCreatedAt();
            if (null !== $createdAt) {
                $sheet->setCellValue('G'.$row, $createdAt->format('Y-m-d H:i:s'));
            } else {
                $sheet->setCellValue('G'.$row, 'N/A');
            }
            $updatedAt = $comment->getUpdatedAt();
            if (null !== $updatedAt) {
                $sheet->setCellValue('H'.$row, $updatedAt->format('Y-m-d H:i:s'));
            } else {
                $sheet->setCellValue('H'.$row, 'N/A');
            }

            ++$row;
        }

        // Save the report
        $writer = new Xlsx($spreadsheet);
        $filename = 'blog_post_comment_report_'.date('Y-m-d_H-i-s').'.xlsx';
        $filePath = $documentPath.$filename;
        $writer->save($filePath);

        // Save the document details to the database
        $user = $entityManager->getRepository(User::class)->find($userId);
        $document = new Document();
        $document->setPath($path);
        $document->setFilename($filename);
        $document->setUser($user);
        $entityManager->getRepository(Document::class)->save($document);
    }
}

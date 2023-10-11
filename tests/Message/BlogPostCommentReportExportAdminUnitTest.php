<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\BlogPostCommentReportExportAdmin;
use PHPUnit\Framework\TestCase;

class BlogPostCommentReportExportAdminUnitTest extends TestCase
{
    public function testGetUserId(): void
    {
        $userId = 1;

        $blogPostCommentReportExportAdmin = new BlogPostCommentReportExportAdmin($userId);

        self::assertEquals($userId, $blogPostCommentReportExportAdmin->getUserId());
    }
}

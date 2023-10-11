<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\BlogPostReportExportBlogger;
use PHPUnit\Framework\TestCase;

class BlogPostReportExportBloggerUnitTest extends TestCase
{
    public function testGetUserId(): void
    {
        $userId = 1;

        $blogPostReportExportBlogger = new BlogPostReportExportBlogger($userId);

        self::assertEquals($userId, $blogPostReportExportBlogger->getUserId());
    }
}

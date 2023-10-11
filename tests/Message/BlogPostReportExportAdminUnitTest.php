<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\BlogPostReportExportAdmin;
use PHPUnit\Framework\TestCase;

class BlogPostReportExportAdminUnitTest extends TestCase
{
    public function testGetUserId(): void
    {
        $userId = 1;

        $blogPostReportExportAdmin = new BlogPostReportExportAdmin($userId);

        self::assertEquals($userId, $blogPostReportExportAdmin->getUserId());
    }
}

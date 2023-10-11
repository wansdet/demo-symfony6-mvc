<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\BlogPostCommentNotification;
use PHPUnit\Framework\TestCase;

class BlogPostCommentNotificationUnitTest extends TestCase
{
    public function testGetBlogPostCommentId(): void
    {
        $blogPostCommentId = 1;

        $blogPostCommentNotification = new BlogPostCommentNotification($blogPostCommentId);

        self::assertEquals($blogPostCommentId, $blogPostCommentNotification->getBlogPostCommentId());
    }
}

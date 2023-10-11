<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\BlogPost;
use App\Entity\BlogPostComment;
use PHPUnit\Framework\TestCase;

class BlogPostCommentUnitTest extends TestCase
{
    private BlogPostComment $blogPostComment;

    protected function setUp(): void
    {
        $this->blogPostComment = new BlogPostComment();
    }

    public function testGetSetBlogPost(): void
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('Blog post title');
        $this->blogPostComment->setBlogPost($blogPost);
        self::assertEquals($blogPost, $this->blogPostComment->getBlogPost());
    }

    public function testGetSetContent(): void
    {
        $content = 'Blog post comment';
        $this->blogPostComment->setContent($content);
        self::assertEquals($content, $this->blogPostComment->getContent());
    }

    public function testGetSetStatus(): void
    {
        $status = BlogPostComment::STATUS_PUBLISHED;
        $this->blogPostComment->setStatus($status);
        self::assertEquals($status, $this->blogPostComment->getStatus());
    }

    public function testGetSetAuthor(): void
    {
        $author = $this->createMock('App\Entity\User');
        $this->blogPostComment->setAuthor($author);
        self::assertEquals($author, $this->blogPostComment->getAuthor());
    }

    public function testGetSetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->blogPostComment->setCreatedAt($createdAt);
        self::assertEquals($createdAt, $this->blogPostComment->getCreatedAt());
    }

    public function testGetSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->blogPostComment->setUpdatedAt($updatedAt);
        self::assertEquals($updatedAt, $this->blogPostComment->getUpdatedAt());
    }
}

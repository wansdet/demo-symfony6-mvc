<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use PHPUnit\Framework\TestCase;

class BlogPostUnitTest extends TestCase
{
    private BlogPost $blogPost;

    protected function setUp(): void
    {
        $this->blogPost = new BlogPost();
    }

    public function testGetSetContent(): void
    {
        $content = 'Blog post content';
        $this->blogPost->setContent($content);
        self::assertEquals($content, $this->blogPost->getContent());
    }

    public function testGetSetSlug(): void
    {
        $slug = 'blog-post-title';
        $this->blogPost->setSlug($slug);
        self::assertEquals($slug, $this->blogPost->getSlug());
    }

    public function testGetSetSummary(): void
    {
        $summary = 'Blog post summary';
        $this->blogPost->setSummary($summary);
        self::assertEquals($summary, $this->blogPost->getSummary());
    }

    public function testGetSetTitle(): void
    {
        $title = 'Blog post title';
        $this->blogPost->setTitle($title);
        self::assertEquals($title, $this->blogPost->getTitle());
    }

    public function testGetSetStatus(): void
    {
        $status = BlogPost::STATUS_PUBLISHED;
        $this->blogPost->setStatus($status);
        self::assertEquals($status, $this->blogPost->getStatus());
    }

    public function testGetSetAuthor(): void
    {
        $author = $this->createMock('App\Entity\User');
        $this->blogPost->setAuthor($author);
        self::assertEquals($author, $this->blogPost->getAuthor());
    }

    public function testGetSetCategory(): void
    {
        $category = $this->createMock(BlogCategory::class);
        $this->blogPost->setBlogCategory($category);
        self::assertEquals($category, $this->blogPost->getBlogCategory());
    }

    public function testGetSetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->blogPost->setCreatedAt($createdAt);
        self::assertEquals($createdAt, $this->blogPost->getCreatedAt());
    }

    public function testGetSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->blogPost->setUpdatedAt($updatedAt);
        self::assertEquals($updatedAt, $this->blogPost->getUpdatedAt());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\BlogPost;
use App\Entity\BlogPostImage;
use PHPUnit\Framework\TestCase;

class BlogPostImageUnitTest extends TestCase
{
    private BlogPostImage $blogPostImage;

    protected function setUp(): void
    {
        $this->blogPostImage = new BlogPostImage();
    }

    public function testGetSetBlogPost(): void
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('Blog post title');
        $this->blogPostImage->setBlogPost($blogPost);
        self::assertEquals($blogPost, $this->blogPostImage->getBlogPost());
    }

    public function testGetSetPath(): void
    {
        $path = 'assets/blog/post/images';
        $this->blogPostImage->setPath($path);
        self::assertEquals($path, $this->blogPostImage->getPath());
    }

    public function testGetSetFilename(): void
    {
        $filename = 'blog-post-image-title.jpg';
        $this->blogPostImage->setFilename($filename);
        self::assertEquals($filename, $this->blogPostImage->getFilename());
    }

    public function testGetSetCaption(): void
    {
        $caption = 'Blog post image caption';
        $this->blogPostImage->setCaption($caption);
        self::assertEquals($caption, $this->blogPostImage->getCaption());
    }

    public function testGetSetCreateAt(): void
    {
        $createdAt = new \DateTime();
        $this->blogPostImage->setCreatedAt($createdAt);
        self::assertEquals($createdAt, $this->blogPostImage->getCreatedAt());
    }

    public function testGetSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->blogPostImage->setUpdatedAt($updatedAt);
        self::assertEquals($updatedAt, $this->blogPostImage->getUpdatedAt());
    }
}

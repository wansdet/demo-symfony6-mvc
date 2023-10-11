<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\BlogCategory;
use App\Entity\BlogPost;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class BlogCategoryUnitTest extends TestCase
{
    private BlogCategory $blogCategory;

    protected function setUp(): void
    {
        $this->blogCategory = new BlogCategory();
    }

    public function testGetAddRemoveBlogPosts(): void
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('Test Blog post Title');
        $blogPost->setContent('Test blog post content');

        $this->blogCategory->addBlogPost($blogPost);
        self::assertInstanceOf(ArrayCollection::class, $this->blogCategory->getBlogPosts());
        self::assertEquals(1, $this->blogCategory->getBlogPosts()->count());

        $this->blogCategory->removeBlogPost($blogPost);
        self::assertEquals(0, $this->blogCategory->getBlogPosts()->count());
    }

    public function testGetSetActive(): void
    {
        $active = true;
        $this->blogCategory->setActive($active);
        self::assertEquals($active, $this->blogCategory->isActive());
    }

    public function testGetSetName(): void
    {
        $name = 'Leisure';
        $this->blogCategory->setName($name);
        self::assertEquals($name, $this->blogCategory->getName());
    }

    public function testGetSetSortOrder(): void
    {
        $sortOrder = 6;
        $this->blogCategory->setSortOrder($sortOrder);
        self::assertEquals($sortOrder, $this->blogCategory->getSortOrder());
    }

    public function testGetSetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->blogCategory->setCreatedAt($createdAt);
        self::assertEquals($createdAt, $this->blogCategory->getCreatedAt());
    }

    public function testGetSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->blogCategory->setUpdatedAt($updatedAt);
        self::assertEquals($updatedAt, $this->blogCategory->getUpdatedAt());
    }
}

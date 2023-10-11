<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\BlogPost;
use App\Entity\BlogPostComment;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testAddRemoveBlogPost(): void
    {
        $blogPost = new BlogPost();
        $this->user->addBlogPost($blogPost);
        self::assertCount(1, $this->user->getBlogPosts());
        self::assertEquals($this->user, $blogPost->getAuthor());
        $this->user->removeBlogPost($blogPost);
        self::assertCount(0, $this->user->getBlogPosts());
        self::assertNull($blogPost->getAuthor());
    }

    public function testAddRemoveBlogPostComment(): void
    {
        $blogPostComment = new BlogPostComment();
        $this->user->addBlogPostComment($blogPostComment);
        self::assertCount(1, $this->user->getBlogPostComments());
        self::assertEquals($this->user, $blogPostComment->getAuthor());
        $this->user->removeBlogPostComment($blogPostComment);
        self::assertCount(0, $this->user->getBlogPostComments());
        self::assertNull($blogPostComment->getAuthor());
    }

    public function testGetSetEmail(): void
    {
        $email = 'test.user@example.com';
        $this->user->setEmail($email);
        self::assertEquals($email, $this->user->getEmail());
    }

    public function testGetSetFirstName(): void
    {
        $firstName = 'Alana';
        $this->user->setFirstName($firstName);
        self::assertEquals($firstName, $this->user->getFirstName());
    }

    public function testGetSetLastName(): void
    {
        $lastName = 'Anderson';
        $this->user->setLastName($lastName);
        self::assertEquals($lastName, $this->user->getLastName());
    }

    public function testGetSetPassword(): void
    {
        $password = 'password';
        $this->user->setPassword($password);
        self::assertEquals($password, $this->user->getPassword());
    }

    public function testGetSetRoles(): void
    {
        $roles = ['ROLE_USER'];
        $this->user->setRoles($roles);
        self::assertEquals($roles, $this->user->getRoles());
    }

    public function testGetSetStatus(): void
    {
        $status = User::STATUS_ACTIVE;
        $this->user->setStatus($status);
        self::assertEquals($status, $this->user->getStatus());
    }

    public function testGetSetJobTitle(): void
    {
        $jobTitle = 'Software Engineer';
        $this->user->setJobTitle($jobTitle);
        self::assertEquals($jobTitle, $this->user->getJobTitle());
    }

    public function testGetSetCreatedAt(): void
    {
        $createdAt = new \DateTime();
        $this->user->setCreatedAt($createdAt);
        self::assertEquals($createdAt, $this->user->getCreatedAt());
    }

    public function testGetSetUpdatedAt(): void
    {
        $updatedAt = new \DateTime();
        $this->user->setUpdatedAt($updatedAt);
        self::assertEquals($updatedAt, $this->user->getUpdatedAt());
    }

    public function testGetUserIdentifier(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        self::assertEquals($email, $this->user->getUserIdentifier());
    }
}

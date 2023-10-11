<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
#[UniqueEntity(fields: ['slug'], message: 'There is already a slug with this value')]
class BlogPost
{
    public const STATUS_ARCHIVED = 'archived';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_SUBMITTED = 'submitted';

    public const BLOG_POST_STATUSES = [
        self::STATUS_ARCHIVED,
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
        self::STATUS_REJECTED,
        self::STATUS_SUBMITTED,
    ];

    public const TRANSITION_ARCHIVE = 'archive';
    public const TRANSITION_DELETE = 'delete';
    public const TRANSITION_PUBLISH = 'publish';
    public const TRANSITION_REJECT = 'reject';
    public const TRANSITION_SUBMIT = 'submit';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private ?string $content = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: self::BLOG_POST_STATUSES)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: BlogCategory::class, inversedBy: 'blogPosts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BlogCategory $blogCategory = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'blogPosts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'blogPost', targetEntity: BlogPostImage::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $blogPostImages;

    #[ORM\OneToMany(mappedBy: 'blogPost', targetEntity: BlogPostComment::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $blogPostComments;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->blogPostImages = new ArrayCollection();
        $this->blogPostComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBlogCategory(): ?BlogCategory
    {
        return $this->blogCategory;
    }

    public function setBlogCategory(?BlogCategory $blogCategory): static
    {
        $this->blogCategory = $blogCategory;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, BlogPostImage>
     */
    public function getBlogPostImages(): Collection
    {
        return $this->blogPostImages;
    }

    public function addBlogPostImage(BlogPostImage $blogPostImage): static
    {
        if (!$this->blogPostImages->contains($blogPostImage)) {
            $this->blogPostImages->add($blogPostImage);
            $blogPostImage->setBlogPost($this);
        }

        return $this;
    }

    public function removeBlogPostImage(BlogPostImage $blogPostImage): static
    {
        if ($this->blogPostImages->removeElement($blogPostImage)) {
            // set the owning side to null (unless already changed)
            if ($blogPostImage->getBlogPost() === $this) {
                $blogPostImage->setBlogPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BlogPostComment>
     */
    public function getBlogPostComments(): Collection
    {
        return $this->blogPostComments;
    }

    public function addBlogPostComment(BlogPostComment $blogPostComment): static
    {
        if (!$this->blogPostComments->contains($blogPostComment)) {
            $this->blogPostComments->add($blogPostComment);
            $blogPostComment->setBlogPost($this);
        }

        return $this;
    }

    public function removeBlogPostComment(BlogPostComment $blogPostComment): static
    {
        if ($this->blogPostComments->removeElement($blogPostComment)) {
            // set the owning side to null (unless already changed)
            if ($blogPostComment->getBlogPost() === $this) {
                $blogPostComment->setBlogPost(null);
            }
        }

        return $this;
    }
}

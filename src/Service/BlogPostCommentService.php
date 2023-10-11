<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BlogPostComment;
use App\Entity\User;
use App\Repository\BlogPostCommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final readonly class BlogPostCommentService
{
    public function __construct(
        private BlogPostCommentRepository $blogPostCommentRepository,
        private TagAwareCacheInterface $cache,
        private PaginatorInterface $paginator
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(BlogPostComment $blogPostComment): void
    {
        $this->blogPostCommentRepository->delete($blogPostComment);

        // clear cache for blog post comment and indexes
        $this->cache->delete('blog_post_comment_'.$blogPostComment->getId());
        $this->cache->invalidateTags(['blog_post_comment_index']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function find(int $id): ?BlogPostComment
    {
        $cacheKey = 'blog_post_comment_%d';

        // Try to retrieve data from the cache
        return $this->cache->get(sprintf($cacheKey, $id), function (ItemInterface $item) use ($id) {
            // var_dump('cache add: blog_post_comment_'.$id);
            $blogPostComment = $this->blogPostCommentRepository->find($id);
            $item->expiresAfter((int) $_ENV['CACHE_DURATION']);

            return $blogPostComment;
        });
    }

    /**
     * @return PaginationInterface<BlogPostComment>
     *
     * @throws InvalidArgumentException
     */
    public function findAll(int $page, int $perPage): PaginationInterface
    {
        return $this->findByQuery($page, $perPage, 'blog_post_comment_index_', [], ['createdAt' => 'DESC']);
    }

    /**
     * @return PaginationInterface<BlogPostComment>
     *
     * @throws InvalidArgumentException
     */
    public function findAllByAuthor(int $page, int $perPage, User $author): PaginationInterface
    {
        return $this->findByQuery($page, $perPage, 'blog_post_comment_index_author_', ['author' => $author], ['createdAt' => 'DESC']);
    }

    /**
     * @return PaginationInterface<BlogPostComment>
     *
     * @throws InvalidArgumentException
     */
    public function findAllPublished(int $page, int $perPage): PaginationInterface
    {
        $criteria = ['status' => BlogPostComment::STATUS_PUBLISHED];

        return $this->findByQuery($page, $perPage, 'blog_post_comment_index_published_', $criteria, ['createdAt' => 'DESC']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function save(BlogPostComment $blogPostComment): void
    {
        $this->blogPostCommentRepository->save($blogPostComment);

        // clear cache for blog post and indexes
        $this->cache->delete('blog_post_comment_'.$blogPostComment->getId());
        $this->cache->invalidateTags(['blog_post_comment_index']);
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return PaginationInterface<BlogPostComment>
     *
     * @throws InvalidArgumentException
     */
    private function findByQuery(int $page, int $perPage, string $keyPrefix, array $criteria = [], array $orderBy = null): PaginationInterface
    {
        $cacheKey = $keyPrefix.$page;

        // Try to retrieve data from the cache
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($page, $perPage, $criteria, $orderBy) {
            // var_dump('cache add: '.$cacheKey);
            $queryBuilder = $this->blogPostCommentRepository->findByQuery($criteria, $orderBy, $perPage, ($page - 1) * $perPage);

            $item->tag(['blog_post_comment_index']);
            $item->expiresAfter((int) $_ENV['CACHE_DURATION']);

            // Paginate the query
            return $this->paginator->paginate(
                $queryBuilder,  // Query to paginate
                $page,          // Current page number
                $perPage        // Number of items per page
            );
        });
    }
}

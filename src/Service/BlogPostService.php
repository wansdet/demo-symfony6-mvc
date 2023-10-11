<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Repository\BlogPostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final readonly class BlogPostService
{
    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private TagAwareCacheInterface $cache,
        private PaginatorInterface $paginator
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(BlogPost $blogPost): void
    {
        $this->blogPostRepository->delete($blogPost);

        // clear cache for blog post and indexes
        $this->cache->delete('blog_post_'.$blogPost->getId());
        $this->cache->invalidateTags(['blog_post_index']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function find(int $id): ?BlogPost
    {
        $cacheKey = 'blog_post_%d';

        // Try to retrieve data from the cache
        return $this->cache->get(sprintf($cacheKey, $id), function (ItemInterface $item) use ($id) {
            // var_dump('cache add: blog_post_'.$id);
            $blogPost = $this->blogPostRepository->find($id);
            $item->expiresAfter((int) $_ENV['CACHE_DURATION']);

            return $blogPost;
        });
    }

    /**
     * @return PaginationInterface<BlogPost>
     *
     * @throws InvalidArgumentException
     */
    public function findAll(int $page, int $perPage): PaginationInterface
    {
        return $this->findByQuery($page, $perPage, 'blog_post_index_', [], ['createdAt' => 'DESC']);
    }

    /**
     * @return PaginationInterface<BlogPost>
     *
     * @throws InvalidArgumentException
     */
    public function findAllByAuthor(int $page, int $perPage, User $author): PaginationInterface
    {
        return $this->findByQuery($page, $perPage, 'blog_post_index_author_', ['author' => $author], ['createdAt' => 'DESC']);
    }

    /**
     * @return PaginationInterface<BlogPost>
     *
     * @throws InvalidArgumentException
     */
    public function findAllPublished(int $page, int $perPage): PaginationInterface
    {
        $criteria = ['status' => BlogPost::STATUS_PUBLISHED];

        return $this->findByQuery($page, $perPage, 'blog_post_index_published_', $criteria, ['createdAt' => 'DESC']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function save(BlogPost $blogPost): void
    {
        $this->blogPostRepository->save($blogPost);

        // clear cache for blog post and indexes
        $this->cache->delete('blog_post_'.$blogPost->getId());
        $this->cache->invalidateTags(['blog_post_index']);
    }

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return PaginationInterface<BlogPost>
     *
     * @throws InvalidArgumentException
     */
    private function findByQuery(int $page, int $perPage, string $keyPrefix, array $criteria = [], array $orderBy = null): PaginationInterface
    {
        $cacheKey = $keyPrefix.$page;

        // Try to retrieve data from the cache
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($page, $perPage, $criteria, $orderBy) {
            // var_dump('cache add: '.$cacheKey);
            $queryBuilder = $this->blogPostRepository->findByQuery($criteria, $orderBy, $perPage, ($page - 1) * $perPage);

            $item->tag(['blog_post_index']);
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

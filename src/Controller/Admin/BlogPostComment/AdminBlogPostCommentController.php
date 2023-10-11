<?php

declare(strict_types=1);

namespace App\Controller\Admin\BlogPostComment;

use App\Entity\BlogPostComment;
use App\Service\BlogPostCommentService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/blog/post/comment')]
class AdminBlogPostCommentController extends AbstractController
{
    public function __construct(private readonly BlogPostCommentService $blogPostCommentService)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_MODERATOR')]
    #[Route('/', name: 'app_blog_post_comment_admin_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = 10;
        $pagination = $this->blogPostCommentService->findAll($page, $perPage);

        return $this->render('blog_post_comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[IsGranted('ROLE_MODERATOR')]
    #[Route('/{id}/manage', name: 'app_blog_post_comment_manage', methods: ['GET'])]
    public function edit(BlogPostComment $blogPostComment): Response
    {
        return $this->render('blog_post_comment/manage.html.twig', [
            'blog_post_comment' => $blogPostComment,
        ]);
    }
}

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
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/admin/blog/post/comment')]
class AdminBlogPostCommentStateController extends AbstractController
{
    public function __construct(
        private readonly BlogPostCommentService $blogPostCommentService,
        private readonly WorkflowInterface $blogPostCommentPublishing
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_MODERATOR')]
    #[Route('/{id}/reject', name: 'app_blog_post_comment_reject', methods: ['POST'])]
    public function reject(Request $request, BlogPostComment $blogPostComment): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('reject'.$blogPostComment->getId(), $token)) {
            if ($this->blogPostCommentPublishing->can($blogPostComment, BlogPostComment::TRANSITION_REJECT)) {
                $this->blogPostCommentPublishing->apply($blogPostComment, BlogPostComment::TRANSITION_REJECT);
                $blogPostComment->setUpdatedAt(new \DateTimeImmutable());
                $this->blogPostCommentService->save($blogPostComment);

                $this->addFlash('success', 'The comment has been rejected.');
            } else {
                $this->addFlash('danger', 'You cannot reject this comment. Please contact the administrator.');
            }
        }

        return $this->redirectToRoute('app_blog_post_comment_manage', [
            'id' => $blogPostComment->getId(),
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_MODERATOR')]
    #[Route('/{id}', name: 'app_blog_post_comment_delete', methods: ['POST'])]
    public function delete(Request $request, BlogPostComment $blogPostComment): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('delete'.$blogPostComment->getId(), $token)) {
            if ($this->blogPostCommentPublishing->can($blogPostComment, BlogPostComment::TRANSITION_DELETE)) {
                $this->blogPostCommentPublishing->apply($blogPostComment, BlogPostComment::TRANSITION_DELETE);
                $this->blogPostCommentService->delete($blogPostComment);
            } else {
                $this->addFlash('danger', 'You cannot delete this comment. Please contact the administrator.');

                return $this->redirectToRoute('app_blog_post_comment_manage', [
                    'id' => $blogPostComment->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_blog_post_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}

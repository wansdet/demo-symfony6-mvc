<?php

declare(strict_types=1);

namespace App\Controller\Admin\BlogPost;

use App\Entity\BlogPost;
use App\Service\BlogPostService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/admin/blog/post')]
class AdminBlogPostStateController extends AbstractController
{
    public function __construct(
        private readonly BlogPostService $blogPostService,
        private readonly WorkflowInterface $blogPublishing
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/{id}/submit', name: 'app_blog_post_submit', methods: ['POST'])]
    public function submit(Request $request, BlogPost $blogPost): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('submit'.$blogPost->getId(), $token)) {
            if ($this->blogPublishing->can($blogPost, BlogPost::TRANSITION_SUBMIT)) {
                $this->blogPublishing->apply($blogPost, BlogPost::TRANSITION_SUBMIT);
                $blogPost->setUpdatedAt(new \DateTimeImmutable());
                $this->blogPostService->save($blogPost);
            } else {
                $this->addFlash('danger', 'Blog post cannot be submitted. Please contact the administrator.');

                return $this->redirectToRoute('app_blog_post_edit', [
                    'id' => $blogPost->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_blog_post_blogger_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_EDITOR')]
    #[Route('/{id}/reject', name: 'app_blog_post_reject', methods: ['POST'])]
    public function reject(Request $request, BlogPost $blogPost): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('reject'.$blogPost->getId(), $token)) {
            if ($this->blogPublishing->can($blogPost, BlogPost::TRANSITION_REJECT)) {
                $this->blogPublishing->apply($blogPost, BlogPost::TRANSITION_REJECT);
                $blogPost->setUpdatedAt(new \DateTimeImmutable());
                $this->blogPostService->save($blogPost);
            } else {
                $this->addFlash('danger', 'Blog post cannot be rejected. Please contact the administrator.');

                return $this->redirectToRoute('app_blog_post_manage', [
                    'id' => $blogPost->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_blog_post_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_EDITOR')]
    #[Route('/{id}/publish', name: 'app_blog_post_publish', methods: ['POST'])]
    public function publish(Request $request, BlogPost $blogPost): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('publish'.$blogPost->getId(), $token)) {
            if ($this->blogPublishing->can($blogPost, BlogPost::TRANSITION_PUBLISH)) {
                $this->blogPublishing->apply($blogPost, BlogPost::TRANSITION_PUBLISH);
                $blogPost->setUpdatedAt(new \DateTimeImmutable());
                $this->blogPostService->save($blogPost);
            } else {
                $this->addFlash('danger', 'Blog post cannot be published. Please contact the administrator.');

                return $this->redirectToRoute('app_blog_post_manage', [
                    'id' => $blogPost->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_blog_post_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_EDITOR')]
    #[Route('/{id}/archive', name: 'app_blog_post_archive', methods: ['POST'])]
    public function archive(Request $request, BlogPost $blogPost): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('archive'.$blogPost->getId(), $token)) {
            if ($this->blogPublishing->can($blogPost, BlogPost::TRANSITION_ARCHIVE)) {
                $this->blogPublishing->apply($blogPost, BlogPost::TRANSITION_ARCHIVE);
                $blogPost->setUpdatedAt(new \DateTimeImmutable());
                $this->blogPostService->save($blogPost);
            } else {
                $this->addFlash('danger', 'Blog post cannot be archived. Please contact the administrator.');

                return $this->redirectToRoute('app_blog_post_manage', [
                    'id' => $blogPost->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_blog_post_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/{id}/delete', name: 'app_blog_post_delete', methods: ['POST'])]
    public function delete(Request $request, BlogPost $blogPost): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $token)) {
            if ($this->blogPublishing->can($blogPost, BlogPost::TRANSITION_DELETE)) {
                $this->blogPublishing->apply($blogPost, BlogPost::TRANSITION_DELETE);
                $this->blogPostService->delete($blogPost);
            } else {
                $this->addFlash('danger', 'Blog post cannot be deleted. Please contact the administrator.');

                return $this->redirectToRoute('app_blog_post_edit', [
                    'id' => $blogPost->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_blog_post_blogger_index', [], Response::HTTP_SEE_OTHER);
    }
}

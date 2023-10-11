<?php

declare(strict_types=1);

namespace App\Controller\Admin\BlogPost;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Form\BlogPostType;
use App\Service\BlogPostService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/admin/blog/post')]
class AdminBlogPostController extends AbstractController
{
    public function __construct(
        private readonly BlogPostService $blogPostService,
        private readonly WorkflowInterface $blogPublishing
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_EDITOR')]
    #[Route('/admin_index', name: 'app_blog_post_admin_index', methods: ['GET'])]
    public function adminIndex(Request $request): Response
    {
        $page = (int) $request->query->get('page', '1');
        $perPage = 10;
        $pagination = $this->blogPostService->findAll($page, $perPage);

        return $this->render('blog_post/admin_index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/blogger_index', name: 'app_blog_post_blogger_index', methods: ['GET'])]
    public function bloggerIndex(Request $request): Response
    {
        $page = (int) $request->query->get('page', '1');
        $perPage = 10;
        /** @var User $author */
        $author = $this->getUser();
        $pagination = $this->blogPostService->findAllByAuthor($page, $perPage, $author);

        return $this->render('blog_post/blogger_index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/blogger_show/{slug}', name: 'app_blog_post_blogger_show', methods: ['GET'])]
    public function bloggerShow(BlogPost $blogPost): Response
    {
        $id = $blogPost->getId();

        if (null == $id) {
            $this->addFlash('error', 'Blog post not found.');
            return $this->redirectToRoute('app_blog_post_blogger_index');
        }

        return $this->render('blog_post/blogger_show.html.twig', [
            'blog_post' => $this->blogPostService->find($id),
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_EDITOR')]
    #[Route('/admin_show/{slug}', name: 'app_blog_post_admin_show', methods: ['GET'])]
    public function adminShow(BlogPost $blogPost): Response
    {
        $id = $blogPost->getId();

        if (null == $id) {
            $this->addFlash('error', 'Blog post not found.');
            return $this->redirectToRoute('app_blog_post_admin_index');
        }

        return $this->render('blog_post/admin_show.html.twig', [
            'blog_post' => $this->blogPostService->find($id),
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/new', name: 'app_blog_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $blogPost = new BlogPost();
        $blogPost->setStatus(BlogPost::STATUS_DRAFT);

        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->setStatus(BlogPost::STATUS_DRAFT);
            /** @var User $author */
            $author = $this->getUser();
            $blogPost->setAuthor($author);
            $this->blogPostService->save($blogPost);

            $this->addFlash('success', 'Blog post created.');

            return $this->redirectToRoute('app_blog_post_edit', [
                'id' => $blogPost->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'blogPostForm' => $form,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/{id}/edit', name: 'app_blog_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogPost $blogPost): Response
    {
        if (!$this->blogPublishing->can($blogPost, BlogPost::TRANSITION_SUBMIT)) {
            throw $this->createAccessDeniedException('You cannot edit this blog post.');
        }

        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->setUpdatedAt(new \DateTimeImmutable());
            $this->blogPostService->save($blogPost);

            $this->addFlash('success', 'Blog post updated.');
        }

        return $this->render('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'blogPostForm' => $form,
        ]);
    }

    #[IsGranted('ROLE_EDITOR')]
    #[Route('/{id}/manage', name: 'app_blog_post_manage', methods: ['GET'])]
    public function manage(BlogPost $blogPost): Response
    {
        return $this->render('blog_post/manage.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }
}

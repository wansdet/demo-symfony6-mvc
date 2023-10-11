<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\BlogCategory;
use App\Form\BlogCategoryType;
use App\Repository\BlogCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/blog/category')]
class AdminBlogCategoryController extends AbstractController
{
    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/', name: 'app_blog_category_index', methods: ['GET'])]
    public function index(BlogCategoryRepository $blogCategoryRepository): Response
    {
        return $this->render('blog_category/index.html.twig', [
            'blog_categories' => $blogCategoryRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/new', name: 'app_blog_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BlogCategoryRepository $blogCategoryRepository): Response
    {
        $blogCategory = new BlogCategory();
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogCategoryRepository->save($blogCategory);

            $this->addFlash('success', 'Blog category created.');

            return $this->redirectToRoute('app_blog_category_edit', [
                'id' => $blogCategory->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_category/new.html.twig', [
            'blog_category' => $blogCategory,
            'blogCategoryForm' => $form,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/{id}', name: 'app_blog_category_show', methods: ['GET'])]
    public function show(BlogCategory $blogCategory): Response
    {
        return $this->render('blog_category/show.html.twig', [
            'blog_category' => $blogCategory,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/{id}/edit', name: 'app_blog_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogCategory $blogCategory, BlogCategoryRepository $blogCategoryRepository): Response
    {
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogCategory->setUpdatedAt(new \DateTimeImmutable());
            $blogCategoryRepository->save($blogCategory);

            $this->addFlash('success', 'Blog category updated.');
        }

        return $this->render('blog_category/edit.html.twig', [
            'blog_category' => $blogCategory,
            'blogCategoryForm' => $form,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/{id}', name: 'app_blog_category_delete', methods: ['POST'])]
    public function delete(Request $request, BlogCategory $blogCategory, BlogCategoryRepository $blogCategoryRepository): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('_token', null);

        if ($this->isCsrfTokenValid('delete'.$blogCategory->getId(), $token)) {
            $blogCategoryRepository->delete($blogCategory);
        }

        return $this->redirectToRoute('app_blog_category_index', [], Response::HTTP_SEE_OTHER);
    }
}

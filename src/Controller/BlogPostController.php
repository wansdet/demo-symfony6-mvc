<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\BlogPostComment;
use App\Entity\User;
use App\Form\BlogPostCommentType;
use App\Message\BlogPostCommentNotification;
use App\Service\BlogPostCommentService;
use App\Service\BlogPostService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/blog/post')]
class BlogPostController extends AbstractController
{
    public function __construct(
        private readonly BlogPostService $blogPostService,
        private readonly BlogPostCommentService $blogPostCommentService,
        private readonly WorkflowInterface $blogPostCommentPublishing,
        private readonly MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'app_blog_post_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = (int) $request->query->get('page', '1');
        $perPage = 10;
        $pagination = $this->blogPostService->findAllPublished($page, $perPage);

        return $this->render('blog_post/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{slug}', name: 'app_blog_post_show', methods: ['GET', 'POST'])]
    public function show(Request $request, BlogPost $blogPost): Response
    {
        $blogPostComment = new BlogPostComment();
        $form = $this->createForm(BlogPostCommentType::class, $blogPostComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->blogPostCommentPublishing->can($blogPostComment, BlogPostComment::TRANSITION_PUBLISH)) {
                $this->blogPostCommentPublishing->apply($blogPostComment, BlogPostComment::TRANSITION_PUBLISH);
                $blogPostComment->setBlogPost($blogPost);
                /** @var User $author */
                $author = $this->getUser();
                $blogPostComment->setAuthor($author);
                $this->blogPostCommentService->save($blogPostComment);

                if (null !== $blogPostComment->getId()) {
                    $this->bus->dispatch(new BlogPostCommentNotification($blogPostComment->getId()));
                    $this->addFlash('success', 'Your comment was added.');
                } else {
                    $this->addFlash('error', 'Your comment could not be added. Please try again or contact support.');
                }
            } else {
                $this->addFlash('error', 'Your comment could not be added.');
            }

            // Create a new instance of the form to clear the comment field
            $blogPostComment = new BlogPostComment();
            $form = $this->createForm(BlogPostCommentType::class, $blogPostComment);
        }

        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
            'commentForm' => $form,
        ]);
    }
}

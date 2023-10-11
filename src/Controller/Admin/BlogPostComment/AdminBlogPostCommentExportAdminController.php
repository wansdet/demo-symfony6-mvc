<?php

declare(strict_types=1);

namespace App\Controller\Admin\BlogPostComment;

use App\Entity\User;
use App\Message\BlogPostCommentReportExportAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminBlogPostCommentExportAdminController extends AbstractController
{
    #[IsGranted('ROLE_EDITOR')]
    #[Route('/admin/blog/post/comment/export/admin', name: 'app_blog_post_comment_export_admin', methods: ['POST'])]
    public function __invoke(MessageBusInterface $bus): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (null === $user->getId()) {
            $this->addFlash('error', 'There was a problem with your export. Please try again or contact support.');
        } else {
            $bus->dispatch(new BlogPostCommentReportExportAdmin($user->getId()));

            $this->addFlash('success', 'Export queued. An email will be sent when it is ready.');
        }

        return $this->redirectToRoute('app_blog_post_comment_admin_index');
    }
}

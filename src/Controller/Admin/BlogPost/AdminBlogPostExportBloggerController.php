<?php

declare(strict_types=1);

namespace App\Controller\Admin\BlogPost;

use App\Entity\User;
use App\Message\BlogPostReportExportBlogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminBlogPostExportBloggerController extends AbstractController
{
    #[IsGranted('ROLE_BLOGGER')]
    #[Route('/admin/blog/post/export/blogger', name: 'app_blog_post_export_blogger', methods: ['POST'])]
    public function __invoke(MessageBusInterface $bus): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (null === $user->getId()) {
            $this->addFlash('error', 'There was a problem with your export. Please try again or contact support.');
        } else {
            $bus->dispatch(new BlogPostReportExportBlogger($user->getId()));

            $this->addFlash('success', 'Export queued. An email will be sent when it is ready.');
        }

        return $this->redirectToRoute('app_blog_post_blogger_index');
    }
}

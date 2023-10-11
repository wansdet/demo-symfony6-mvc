<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\DocumentReadyNotification;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DocumentReadyNotificationHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private MailerInterface $mailer,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(DocumentReadyNotification $notification): void
    {
        /** @var User $user */
        $user = $this->userRepository->find($notification->getUserId());

        if (null == $user) {
            throw new \RuntimeException('User not found');
        }

        /** @var string $recipient */
        $recipient = $user->getEmail();
        $subject = $notification->getSubject();
        $content = $notification->getContent();
        $template = 'emails/document_ready_notification.md';
        $link = $_ENV['APP_URL'].'/admin/document/';

        $email = (new TemplatedEmail())
            ->to($recipient)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'heading' => $subject,
                'content' => $content,
                'link' => $link,
            ]);

        $this->mailer->send($email);
    }
}

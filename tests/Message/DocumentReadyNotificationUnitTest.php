<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Message\DocumentReadyNotification;
use PHPUnit\Framework\TestCase;

class DocumentReadyNotificationUnitTest extends TestCase
{
    public function testGetDocumentId(): void
    {
        $documentId = 1;
        $subject = 'Test Subject';
        $content = 'Test Content';

        $documentReadyNotification = new DocumentReadyNotification($documentId, $subject, $content);

        self::assertEquals($documentId, $documentReadyNotification->getUserId());
        self::assertEquals($subject, $documentReadyNotification->getSubject());
        self::assertEquals($content, $documentReadyNotification->getContent());
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EmailStatus;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(protected \App\Models\Email $emailModel, protected MailerInterface $mailer)
    {
    }

    public function sendQueuedEmails(): void
    {
        $emails = $this->emailModel->getEmailsByStatus(EmailStatus::Queue);

        foreach ($emails as $email) {
            $meta = $email->getMeta();

            $emailMessage = (new Email())
            ->from($meta['from'])
            ->to($meta['to'])
            ->subject($email->getSubject())
            ->text($email->getTextBody())
            ->html($email->getHtmlBody());

            $this->mailer->send($emailMessage);

            $this->emailModel->markEmailSent($email->getId());
        }
    }

    public function send(array $to, string $template): bool
    {
        sleep(1);

        return true;
    }
}

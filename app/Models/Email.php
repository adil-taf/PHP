<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use App\Model;
use Symfony\Component\Mime\Address;

class Email extends Model
{
    public function queue(
        Address $to,
        Address $from,
        string $subject,
        string $html,
        ?string $text = null
    ): void {
        $meta['to']   = $to->toString();
        $meta['from'] = $from->toString();

        $email = (new \App\Entity\Email())
            ->setSubject($subject)
            ->setStatus(EmailStatus::Queue)
            ->setTextBody($text)
            ->setHtmlBody($html)
            ->setMeta($meta);

        $this->entityManager->persist($email);
        $this->entityManager->flush();
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder
          ->select('e')
          ->from(\App\Entity\Email::class, 'e')
          ->where('e.status = :status')
          ->setParameter('status', $status->value)
          ->getQuery();

          $emails = $query->getResult();
          return $emails;
    }

    public function markEmailSent(int $id): void
    {
        $email = $this->entityManager->getReference(\App\Entity\Email::class, $id);
        $email->setStatus(EmailStatus::Sent);
        $email->setSentAt(new \DateTime());

        $email = $this->entityManager->merge($email);
        $this->entityManager->flush();
    }
}

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
        $this->db->createQueryBuilder()
        ->insert('emails')
        ->values([
            'subject' => '?',
            'status' => '?',
            'html_body' => '?',
            'text_body' => '?',
            'meta' => '?',
            'created_at' => 'CURRENT_TIMESTAMP',
            'sent_at' => '?'
        ])
        ->setParameter(0, $subject)
        ->setParameter(1, EmailStatus::Queue->value)
        ->setParameter(2, $html)
        ->setParameter(3, $text)
        ->setParameter(4, json_encode($meta))
        ->setParameter(5, '1970-01-01')
        ->execute();
        /*
        $stmt = $this->db->prepare(
            'INSERT INTO emails (subject, status, html_body, text_body, meta, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $meta['to']   = $to->toString();
        $meta['from'] = $from->toString();

        $stmt->executeStatement([$subject, EmailStatus::Queue->value, $html, $text, json_encode($meta)]);
        */
    }

    public function getEmailsByStatus(EmailStatus $status): array
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('emails')
            ->where('status = ?')
            ->setParameter(0, $status->value)
            ->fetchAllAssociative();
        /*
        $stmt = $this->db->prepare(
            'SELECT *
             FROM emails
             WHERE status = ?'
        );

        return $stmt->executeQuery([$status->value])->fetchAllAssociative();
        */
    }

    public function markEmailSent(int $id): void
    {
        $this->db->createQueryBuilder()
           ->update('emails')
           ->set('status', '?')
           ->set('sent_at', 'CURRENT_TIMESTAMP')
           ->where('id = ?')
           ->setParameter(0, EmailStatus::Sent->value)
           ->setParameter(1, $id)
           ->execute();

        /*
        $stmt = $this->db->prepare(
            'UPDATE emails
             SET status = ?, sent_at = NOW()
             WHERE id = ?'
        );

        $stmt->executeStatement([EmailStatus::Sent->value, $id]);
        */
    }
}

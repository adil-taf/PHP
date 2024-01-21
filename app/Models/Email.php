<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use App\Model;
use PDO;
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
        $stmt = $this->db->prepare(
            'INSERT INTO emails (subject, status, html_body, text_body, meta, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );

        $meta['to']   = $to->toString();
        $meta['from'] = $from->toString();

        $stmt->execute([$subject, EmailStatus::Queue->value, $html, $text, json_encode($meta)]);
    }
}

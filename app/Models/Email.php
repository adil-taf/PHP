<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Mime\Address;

class Email extends Model
{
    const UPDATED_AT = null;

    public function queue(
        Address $to,
        Address $from,
        string $subject,
        string $html,
        ?string $text = null
    ): void {
        $this->subject = $subject;
        $this->status         = EmailStatus::Queue->value;
        $this->html_body         = $html;
        $this->text_body = $text;
        $meta['to']   = $to->toString();
        $meta['from'] = $from->toString();
        $this->meta = json_encode($meta);
        $this->created_at = new Carbon();

        $this->save();
    }

    protected static function booted()
    {
        static::creating(function (Email $email) {
            if ($email->isClean('sent_at')) {
                $email->sent_at = '1970-01-01';
            }
        });
    }

    public function getEmailsByStatus(EmailStatus $status): Collection
    {
        return Email::query()->where('status', $status)->get();//->toArray();
    }

    public function markEmailSent(int $id): void
    {
        Email::query()->where('id', $id)->update(['status' => EmailStatus::Sent->value,'sent_at' => new Carbon()]);
    }
}

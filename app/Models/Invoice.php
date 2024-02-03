<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    const UPDATED_AT = null;
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime',
        'status'     => InvoiceStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function (Invoice $invoice) {
            if ($invoice->isClean('created_at')) {
                $invoice->created_at = (new Carbon())->addDays(10);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}

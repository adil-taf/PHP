<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Model;

class Invoice extends Model
{
    public function create(int $invoiceNumber, float $amount, int $userId, InvoiceStatus $status): int
    {
        $this->db->insert('invoices', [
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'user_id' => $userId,
            'status' => $status->value
        ]);

        return (int) $this->db->lastInsertId();

        /***
        $stmt = $this->db->prepare(
            'INSERT INTO invoices (invoice_number, amount, user_id, status)
             VALUES (?, ?, ?, ?)'
        );*/
        //$stmt->execute([$invoiceNumber,  $amount,  $userId,  $status->value]);
        //return (int) $this->db->lastInsertId();
    }

    public function find(int $invoiceId): array
    {
        return $this->db->createQueryBuilder()
            ->select('i.id', 'amount', 'full_name')
            ->from('invoices', 'i')
            ->leftJoin('i', 'users', 'u', 'u.id = user_id')
            ->where('i.id = ?')
            ->setParameter(0, $invoiceId)
            ->fetchAssociative();
    }

    public function all(InvoiceStatus $status): array
    {
        return $this->db->createQueryBuilder()->select('id', 'invoice_number', 'amount', 'status')
            ->from('invoices')
            ->where('status = ?')
            ->setParameter(0, $status->value)
            ->fetchAllAssociative();
    }
}

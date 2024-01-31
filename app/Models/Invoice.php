<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Model;

class Invoice extends Model
{
    public function createInvoiceWithItems(
        array $items,
        int $amount,
        string $invoiceNumber,
        InvoiceStatus $invoiceStatus
    ) {
        $entityManager = \Doctrine\ORM\EntityManager::create(
            $this->db->getParams(),
            \Doctrine\ORM\Tools\Setup::createAttributeMetadataConfiguration([__DIR__ . '/../Entity'])
        );

        //Create Ivoice and InvoiceItem objects and associate them with each other
        $invoice = (new \App\Entity\Invoice())
            ->setAmount($amount)
            ->setInvoiceNumber($invoiceNumber)
            ->setStatus($invoiceStatus)
            ->setCreatedAt(new \DateTime());
        foreach ($items as [$description, $quantity, $unitPrice]) {
            $item = (new \App\Entity\InvoiceItem())
              ->setDescription($description)
              ->setQuantity($quantity)
              ->setUnitPrice($unitPrice);

            $invoice->addItem($item);
        }

        $entityManager->persist($invoice);
        $entityManager->flush();

        //$entityManager->remove($invoice);
        //$entityManager->flush();
    }

    public function create(int $invoiceNumber, float $amount, int $userId, InvoiceStatus $status): int
    {
        $this->db->insert('invoices', [
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'user_id' => $userId,
            'status' => $status->value
        ]);

        return (int) $this->db->lastInsertId();
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

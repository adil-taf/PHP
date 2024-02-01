<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\InvoiceStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[Entity]
#[Table('invoices')]
#[HasLifecycleCallbacks]
class Invoice
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    private float $amount;

    #[Column(name: 'invoice_number')]
    private string $invoiceNumber;

    #[Column]
    private InvoiceStatus $status;

    #[Column(name: 'user_id')]
    private int $userId;

    #[Column(name: 'created_at')]
    private \DateTime $createdAt;

    #[OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    #[PrePersist]
    public function onPrePersist(LifecycleEventArgs $args)
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Invoice
    {
        $this->amount = $amount;

        return $this;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): Invoice
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatus $status): Invoice
    {
        $this->status = $status;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): Invoice
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function addItem(InvoiceItem $item): Invoice
    {
        $item->setInvoice($this);

        $this->items->add($item);

        return $this;
    }
}

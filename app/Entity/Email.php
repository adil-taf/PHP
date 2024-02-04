<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\EmailStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[Entity]
#[Table('emails')]
#[HasLifecycleCallbacks]
class Email
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $subject;

    #[Column]
    private EmailStatus $status;

    #[Column(name: 'text_body')]
    private string $textBody;

    #[Column(name: 'html_body')]
    private string $htmlBody;

    #[Column(type: Types::JSON)]
    private array $meta;

    #[Column(name: 'created_at')]
    private \DateTime $createdAt;

    #[Column(name: 'sent_at')]
    private \DateTime $sentAt;

    #[PrePersist]
    public function onPrePersist(LifecycleEventArgs $args)
    {
        $this->createdAt = new \DateTime();
        $this->sentAt = new \DateTime('1970-01-01');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Email
    {
        $this->subject = $subject;

        return $this;
    }

    public function getStatus(): EmailStatus
    {
        return $this->status;
    }

    public function setStatus(EmailStatus $status): Email
    {
        $this->status = $status;

        return $this;
    }

    public function getTextBody(): string
    {
        return $this->textBody;
    }

    public function setTextBody(string $textBody): Email
    {
        $this->textBody = $textBody;

        return $this;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function setHtmlBody(string $htmlBody): Email
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): Email
    {
        $this->meta = $meta;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setSentAt(\DateTime $sentAt): Email
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getSentAt(): \DateTime
    {
        return $this->sentAt;
    }
}

<?php

namespace App\Entity;

use App\Repository\PromoCodeHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: PromoCodeHistoryRepository::class)]
#[ORM\Table(name: "promo_code_history")]
class PromoCodeHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PromoCode::class)]
    #[ORM\JoinColumn(nullable: false)]
    private PromoCode $promoCode;

    #[ORM\Column(type: "string", length: 255)]
    private string $action;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $details = null;

    #[ORM\Column(type: "datetime")]
    private DateTime $createdAt;

    public function __construct(PromoCode $promoCode, string $action, ?string $details = null)
    {
        $this->promoCode = $promoCode;
        $this->action = $action;
        $this->details = $details;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromoCode(): PromoCode
    {
        return $this->promoCode;
    }

    public function setPromoCode(PromoCode $promoCode): void
    {
        $this->promoCode = $promoCode;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): void
    {
        $this->details = $details;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}

<?php

namespace App\Entity;

use App\Repository\PromoCodeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromoCodeRepository::class)]
#[ORM\Table(name: 'promo_codes')]
class PromoCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $code;

    #[ORM\Column(type: 'integer')]
    private int $displayLimit;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $updatedAt = null;

    public function __construct(string $name, string $code, int $displayLimit)
    {
        $this->name = $name;
        $this->code = $code;
        $this->displayLimit = $displayLimit;
        $this->isActive = true;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->markUpdated();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDisplayLimit(): int
    {
        return $this->displayLimit;
    }

    public function setDisplayLimit(int $displayLimit): void
    {
        $this->displayLimit = $displayLimit;
        $this->markUpdated();
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->markUpdated();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->markUpdated();
    }

    public function decrementDisplayLimit(): void
    {
        if ($this->displayLimit > 0) {
            $this->displayLimit -= 1;
            $this->markUpdated();
        }
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    private function markUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }
}

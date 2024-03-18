<?php

namespace App\Service;

use App\Entity\PromoCode;
use App\Entity\PromoCodeHistory;
use App\Repository\PromoCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PromoCodeService
{
    private PromoCodeRepository $promoCodeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PromoCodeRepository $promoCodeRepository, EntityManagerInterface $entityManager)
    {
        $this->promoCodeRepository = $promoCodeRepository;
        $this->entityManager = $entityManager;
    }

    public function createPromoCode(string $name, string $code, int $displayLimit): PromoCode
    {
        $promoCode = new PromoCode($name, $code, $displayLimit);
        $this->entityManager->persist($promoCode);
        $history = new PromoCodeHistory($promoCode, 'create', "create code");
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        return $promoCode;
    }

    public function updatePromoCode(int $id, string $newName): PromoCode
    {
        $promoCode = $this->getPromoCode($id);
        $originalName = $promoCode->getName();
        $promoCode->setName($newName);
        $history = new PromoCodeHistory($promoCode, 'update', "Name changed from $originalName to $newName");
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        return $promoCode;
    }

    public function deactivatePromoCode(int $id): PromoCode
    {
        $promoCode = $this->getPromoCode($id);
        $promoCode->deactivate();
        $history = new PromoCodeHistory($promoCode, 'deactivate', "Code deactivation");
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        return $promoCode;
    }

    public function deletePromoCode(int $id): void
    {
        $promoCode = $this->getPromoCode($id);
        $this->entityManager->remove($promoCode);
        $history = new PromoCodeHistory($promoCode, 'delete', "Code delete");
        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }

    public function getPromoCode(int $id): PromoCode
    {
        $promoCode = $this->promoCodeRepository->find($id);

        if (!$promoCode) {
            throw new NotFoundHttpException("Promo code not found.");
        }
        $history = new PromoCodeHistory($promoCode, 'get', "Get Code");
        $this->entityManager->persist($history);
        return $promoCode;
    }

    public function updatePromoCodeDisplayLimit(PromoCode $promoCode): void
    {
        $promoCode->decrementDisplayLimit();
        $this->entityManager->persist($promoCode);
        $this->entityManager->flush();
    }
}
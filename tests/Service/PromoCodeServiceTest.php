<?php

namespace App\Tests\Service;

use App\Entity\PromoCode;
use App\Repository\PromoCodeRepository;
use App\Service\PromoCodeService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PromoCodeServiceTest extends TestCase
{
    private $promoCodeRepository;
    private $entityManager;
    private PromoCodeService $promoCodeService;

    protected function setUp(): void
    {
        $this->promoCodeRepository = $this->createMock(PromoCodeRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->promoCodeService = new PromoCodeService($this->promoCodeRepository, $this->entityManager);
    }

    public function testCreatePromoCode()
    {
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(PromoCode::class));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $promoCode = $this->promoCodeService->createPromoCode('Test', 'TESTCODE', 10);

        $this->assertInstanceOf(PromoCode::class, $promoCode);
        $this->assertEquals('Test', $promoCode->getName());
    }

    public function testUpdatePromoCode()
    {
        $promoCode = new PromoCode('Original Name', 'CODE123', 10);

        $this->promoCodeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($promoCode);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $updatedPromoCode = $this->promoCodeService->updatePromoCode(1, 'Updated Name');

        $this->assertInstanceOf(PromoCode::class, $updatedPromoCode);
        $this->assertEquals('Updated Name', $updatedPromoCode->getName());
    }

    public function testDeactivatePromoCode()
    {
        $promoCode = new PromoCode('Test Code', 'CODE123', 10);
        $promoCode->activate();

        $this->promoCodeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($promoCode);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $deactivatedPromoCode = $this->promoCodeService->deactivatePromoCode(1);

        $this->assertInstanceOf(PromoCode::class, $deactivatedPromoCode);
        $this->assertFalse($deactivatedPromoCode->isActive());
    }

    public function testDeletePromoCode()
    {
        $promoCode = new PromoCode('Test Code', 'CODE123', 10);

        $this->promoCodeRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($promoCode);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($promoCode));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->promoCodeService->deletePromoCode(1);
    }
}
<?php

namespace App\Tests\Controller;

use App\Controller\PromoCodeController;
use App\Entity\PromoCode;
use App\Repository\PromoCodeRepository;
use App\Service\PromoCodeService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PromoCodeControllerTest extends TestCase
{
    private $promoCodeServiceMock;
    private $promoCodeRepositoryMock;
    private PromoCodeController $controller;

    protected function setUp(): void
    {
        $this->promoCodeServiceMock = $this->createMock(PromoCodeService::class);
        $this->promoCodeRepositoryMock = $this->createMock(PromoCodeRepository::class);
        $this->controller = new PromoCodeController($this->promoCodeServiceMock, $this->promoCodeRepositoryMock);

        $this->promoCodeRepositoryMock
            ->method('isCodeUnique')
            ->willReturn(true);
    }

    public function testAddPromoCode()
    {
        $request = new Request([], [
            'name' => 'Test Code',
            'code' => 'TEST100',
            'displayLimit' => 10
        ]);
        $expectedPromoCode = new PromoCode('Test Code', 'TEST100', 10);
        $expectedPromoCodeReflection = new \ReflectionObject($expectedPromoCode);
        $idProperty = $expectedPromoCodeReflection->getProperty('id');
        $idProperty->setValue($expectedPromoCode, 1);

        $this->promoCodeServiceMock
            ->expects($this->once())
            ->method('createPromoCode')
            ->with('Test Code', 'TEST100', 10)
            ->willReturn($expectedPromoCode);

        $response = $this->controller->addPromoCode($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testEditPromoCode()
    {
        $request = new Request([], ['name' => 'Updated Name']);
        $promoCodeId = 1;

        $expectedPromoCode = new PromoCode('Updated Name', 'TEST100', 10);

        $this->promoCodeServiceMock
            ->expects($this->once())
            ->method('updatePromoCode')
            ->with($promoCodeId, 'Updated Name')
            ->willReturn($expectedPromoCode);

        $response = $this->controller->editPromoCode($request, $promoCodeId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeactivatePromoCode()
    {
        $promoCodeId = 1;

        $expectedPromoCode = new PromoCode('Test', 'TEST100', 10);

        $this->promoCodeServiceMock
            ->expects($this->once())
            ->method('deactivatePromoCode')
            ->with($promoCodeId)
            ->willReturn($expectedPromoCode);

        $response = $this->controller->deactivatePromoCode($promoCodeId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testViewPromoCode()
    {
        $promoCodeId = 1;

        $expectedPromoCode = new PromoCode('Test Code', 'TEST100', 10);

        $this->promoCodeServiceMock
            ->expects($this->once())
            ->method('getPromoCode')
            ->with($promoCodeId)
            ->willReturn($expectedPromoCode);

        $response = $this->controller->viewPromoCode($promoCodeId);


        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeletePromoCode()
    {
        $promoCodeId = 1;

        $this->promoCodeServiceMock
            ->expects($this->once())
            ->method('deletePromoCode')
            ->with($promoCodeId);

        $response = $this->controller->deletePromoCode($promoCodeId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
<?php

namespace App\Controller;

use App\Entity\PromoCode;
use App\Repository\PromoCodeRepository;
use App\Service\PromoCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromoCodeController extends AbstractController
{
    private PromoCodeService $promoCodeService;
    private PromoCodeRepository $promoCodeRepository;

    public function __construct(PromoCodeService $promoCodeService, PromoCodeRepository $promoCodeRepository)
    {
        $this->promoCodeService = $promoCodeService;
        $this->promoCodeRepository = $promoCodeRepository;
    }

    #[Route('/promo-code/add', name: 'add_promo_code', methods: ['POST'])]
    public function addPromoCode(Request $request): JsonResponse
    {
        $name = $request->request->get('name');
        $code = $request->request->get('code');
        $displayLimit = $request->request->getInt('displayLimit');

        if (!$this->promoCodeRepository->isCodeUnique($code)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Code already exists'], 400);
        }

        if (empty($name) || empty($code) || $displayLimit < 1) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], 400);
        }

        $promoCode = $this->promoCodeService->createPromoCode($name, $code, $displayLimit);

        return new JsonResponse(
            ['status' => 'success', 'data' => $this->serializePromoCode($promoCode)],
            Response::HTTP_CREATED
        );
    }

    #[Route('/promo-code/edit/{id}', name: 'edit_promo_code', methods: ['POST'])]
    public function editPromoCode(Request $request, int $id): JsonResponse
    {
        $newName = $request->request->get('name');

        if (empty($newName)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data provided'], 400);
        }

        try {
            $this->promoCodeService->updatePromoCode($id, $newName);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        return new JsonResponse(['status' => 'success', 'data' => 'Code updated']);
    }

    #[Route('/promo-code/deactivate/{id}', name: 'deactivate_promo_code', methods: ['POST'])]
    public function deactivatePromoCode(int $id): JsonResponse
    {
        try {
            $this->promoCodeService->deactivatePromoCode($id);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        return new JsonResponse(['status' => 'success', 'data' => 'Code deactivated']);
    }

    #[Route('/promo-code/{id}', name: 'view_promo_code', methods: ['GET'])]
    public function viewPromoCode(int $id): JsonResponse
    {
        try {
            $promoCode = $this->promoCodeService->getPromoCode($id);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        if (!$promoCode->isActive() || $promoCode->getDisplayLimit() <= 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'Code is not available for display'], 400);
        }

        $this->promoCodeService->updatePromoCodeDisplayLimit($promoCode);

        return new JsonResponse(['status' => 'success', 'data' => $this->serializePromoCode($promoCode)]);
    }

    #[Route('/promo-code/delete/{id}', name: 'delete_promo_code', methods: ['DELETE'])]
    public function deletePromoCode(int $id): JsonResponse
    {
        try {
            $this->promoCodeService->deletePromoCode($id);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 404);
        }

        return new JsonResponse(['status' => 'success', 'message' => 'Code deleted']);
    }

    private function serializePromoCode(PromoCode $promoCode): array
    {
        return [
            'id' => $promoCode->getId(),
            'name' => $promoCode->getName(),
            'code' => $promoCode->getCode(),
            'displayLimit' => $promoCode->getDisplayLimit(),
            'isActive' => $promoCode->isActive(),
            'createdAt' => $promoCode->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $promoCode->getUpdatedAt() ? $promoCode->getUpdatedAt()->format('Y-m-d H:i:s') : null
        ];
    }
}

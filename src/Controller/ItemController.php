<?php

namespace App\Controller;

use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/item')]
class ItemController extends AbstractController
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    #[Route('/add', methods: ['POST'])]
    public function addItem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $name = $data['name'] ?? null;
        $category = $data['category'] ?? null;
        $subItems = $data['subItems'] ?? [];

        $item = $this->itemService->createItem($name, $category, $subItems);

        return $this->json(['id' => $item->getId()]);
    }

    #[Route('/edit/{id}', methods: ['PUT'])]
    public function editItem(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $item = $this->itemService->getItem($id);

        if (!$item) {
            return $this->json(['error' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
        $this->itemService->updateItem(
            $item,
            $data['name'] ?? $item->getName(),
            $data['category'] ?? $item->getCategory()
        );

        return $this->json(['success' => 'Item updated']);
    }

    #[Route('/get/{id}', methods: ['GET'])]
    public function getItem(int $id, SerializerInterface $serializer): Response
    {
        $item = $this->itemService->getItem($id);
        if (!$item) {
            return $this->json(['error' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        $json = $serializer->serialize($item, 'json', ['groups' => 'item_details']);
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }
}
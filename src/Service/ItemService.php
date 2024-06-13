<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\SubItem;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private ItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ItemRepository $itemRepository, EntityManagerInterface $entityManager)
    {
        $this->itemRepository = $itemRepository;
        $this->entityManager = $entityManager;
    }

    public function createItem(string $name, string $category, array $subItemsData): Item
    {
        $item = new Item();
        $item->setName($name);
        $item->setCategory($category);
        $item->setCreatedAt(new \DateTime());

        foreach ($subItemsData as $sub) {
            $subItem = new SubItem();
            $subItem->setName($sub['name']);
            $subItem->setValue($sub['value']);
            $subItem->setItem($item);
            $this->entityManager->persist($subItem);
        }

        $this->itemRepository->save($item);
        return $item;
    }

    public function updateItem(Item $item, string $name, string $category): void
    {
        $item->setName($name);
        $item->setCategory($category);
        $this->itemRepository->update($item);
    }

    public function getItem(int $id): ?Item
    {
        return $this->itemRepository->findById($id);
    }
}
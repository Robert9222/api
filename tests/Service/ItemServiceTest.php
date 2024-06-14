<?php

namespace App\Tests\Service;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ItemServiceTest extends TestCase
{
    private $entityManager;
    private $itemRepository;
    private $itemService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);
        $this->itemService = new ItemService($this->itemRepository, $this->entityManager);
    }

    public function testCreateItemWithValidData()
    {
        $name = "Testowy przedmiot";
        $category = "Testowa kategoria";
        $subItemsData = [
            ['name' => 'SubItem1', 'value' => 'Wartość1'],
            ['name' => 'SubItem2', 'value' => 'Wartość2']
        ];

        $item = new Item();
        $item->setName($name);
        $item->setCategory($category);

        $this->itemRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($item));

        $this->entityManager->expects($this->exactly(2))
            ->method('persist');

        $createdItem = $this->itemService->createItem($name, $category, $subItemsData);

        $this->assertInstanceOf(Item::class, $createdItem);
        $this->assertEquals($name, $createdItem->getName());
        $this->assertEquals($category, $createdItem->getCategory());
    }
}

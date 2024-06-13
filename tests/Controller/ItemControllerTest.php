<?php

namespace App\Tests\Controller;
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ItemControllerTest extends WebTestCase
{
    public function testAddItem(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/item/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Test Item',
            'category' => 'Test Category',
            'subItems' => [
                ['name' => 'SubItem1', 'value' => 'Value1'],
                ['name' => 'SubItem2', 'value' => 'Value2']
            ]
        ]));

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
    }

    public function testEditItem(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/item/edit/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Updated Name',
            'category' => 'Updated Category'
        ]));

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Item updated', $response['success']);
    }

    public function testGetItem(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/item/get/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('category', $response);
    }
}
<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Volunteer;

class VolunteerControllerTest extends WebTestCase
{
    public function testDefaultPagination()
    {
        $client = static::createClient();
        $client->request('GET', '/api/volunteers');
    
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'current_page' => 1,
            'page_size' => 10,
        ]);
    }

    public function testCustomPagination()
    {
        $client = static::createClient();
        $client->request('GET', '/api/volunteers?page=2&pageSize=5');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'current_page' => 2,
            'page_size' => 5,
        ]);
    }

    public function testVolunteersReturned()
    {
        $this->loadVolunteersIntoDatabase();
        $client = static::createClient();
        $client->request('GET', '/api/volunteers?page=1&pageSize=2');
    
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'total_volunteers' => 5, 
            'total_pages' => 3, 
            'volunteers' => [
                ['full_name' => 'Barry Towne'],
                ['full_name' => 'Ethyl Donnelly'],
            ],
        ]);
    }

    public function testInvalidPageRequest()
    {
        $client = static::createClient();
        $client->request('GET', '/api/volunteers?page=-1&pageSize=10');

        $this->assertResponseIsSuccessful(); 
        $this->assertJsonContains(['current_page' => 1]);
    }

}

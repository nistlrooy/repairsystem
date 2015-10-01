<?php

namespace App\RepairBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExportControllerTest extends WebTestCase
{
    public function testToexcel()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/toexcel');
    }

}

<?php

namespace Gizmo\CapoBundle\Tests\Controller;

use Noma\NomaBundle\Controller\ApiController;
use Noma\NomaBundle\Tests\FunctionalTestCase;

class StatusControllerTest extends FunctionalTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $url = '/';

        $crawler = $client->request('GET', $url);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
    }
}

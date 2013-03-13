<?php

namespace Gizmo\CapoBundle\Tests\Controller;

use Noma\NomaBundle\Controller\ApiController;
use Noma\NomaBundle\Tests\FunctionalTestCase;

class ServicesControllerTest extends FunctionalTestCase
{
    /**
     * Test get nodes
     */
    public function testGetNodes()
    {
        $client = static::createClient();
        $url = '/services/';

        $crawler = $client->request('GET', $url);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
    }
}

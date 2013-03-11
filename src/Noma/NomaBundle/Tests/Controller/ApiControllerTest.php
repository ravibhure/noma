<?php

namespace Gizmo\CapoBundle\Tests\Controller;

use Noma\NomaBundle\Controller\ApiController;
use Noma\NomaBundle\Tests\FunctionalTestCase;

class ApiControllerTest extends FunctionalTestCase
{
    /**
     * Test to make sure GET and POST are treated equally
     */
    public function testGetAndPostAreEqual()
    {
        $client = static::createClient();
        $data = array('nodepropdef' => 1);
        $url = '/api/get_nodeprops/';

        $crawler = $client->request('GET', $url, $data);
        $response1 = $client->getResponse();

        $crawler = $client->request('POST', $url, $data);
        $response2 = $client->getResponse();

        $this->assertEquals($response1->getContent(), $response2->getContent());
    }
}

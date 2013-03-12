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

    public function testGetNodes()
    {
        $client = static::createClient();
        $data = array('exclude_nodeprop' => 1);
        $url = '/api/get_nodes/';

        $crawler = $client->request('GET', $url, $data);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
    }

    public function testNodeAddNodeprop()
    {
        $client = static::createClient();
        $data = array(
            'node' => 1,
            'nodeprop' => 2
        );
        $url = '/api/node_add_nodeprop/';

        $crawler = $client->request('GET', $url, $data);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
    }

    public function testNodeAddNotExistingNodeprop()
    {
        $client = static::createClient();
        $data = array(
            'node' => 1,
            'nodeprop' => 999999999999999999
        );
        $url = '/api/node_add_nodeprop/';

        $crawler = $client->request('GET', $url, $data);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }

    public function testNodeRemoveNodeprop()
    {
        $client = static::createClient();
        $data = array(
            'node' => 1,
            'nodeprop' => 1
        );
        $url = '/api/node_remove_nodeprop/';

        $crawler = $client->request('GET', $url, $data);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
    }

    /**
     * Test removing a nodeprop which does exist but is not linked to the node
     */
    public function testNodeRemoveNotLinkedNodeprop()
    {
        $client = static::createClient();
        $data = array(
            'node' => 1,
            'nodeprop' => 2
        );
        $url = '/api/node_remove_nodeprop/';

        $crawler = $client->request('GET', $url, $data);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
    }

    /**
     * Test removing a nonexisting nodeprop from a node
     */
    public function testNodeRemoveNotExistingNodeprop()
    {
        $client = static::createClient();
        $data = array(
            'node' => 1,
            'nodeprop' => 99999999999999
        );
        $url = '/api/node_remove_nodeprop/';

        $crawler = $client->request('GET', $url, $data);
        $response = $client->getResponse();

        $this->assertTrue($response->isNotFound());
    }
}

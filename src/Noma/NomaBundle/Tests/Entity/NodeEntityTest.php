<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\Node;

class NodeEntityTest extends WebTestCase
{
    public function testNodeName()
    {
    $n = new Node();
    $n->setName('phpunit');
    $result = $n->getName();

    $this->assertEquals($result, 'phpunit');
    }

    public function testNodeIp()
    {
        $n = new Node();
        $n->setIp('1.2.3.4');
        $result = $n->getIp();

        $this->assertEquals($result, '1.2.3.4');
    }

    public function testNodeId()
    {
        $n = new Node();
        $result = $n->getId();

        $this->assertEquals($result, NULL);
    }

    public function testNodeStatus()
    {
        $n = new Node();
        $n->setStatus('1');
        $result = $n->getStatus();

        $this->assertEquals($result, '1');
    }
    
}
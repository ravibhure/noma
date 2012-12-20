<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\Node;

class NodeEntityTest extends WebTestCase
{
    public function testNodeName()
    {
    $n = new Node();
    $name = 'phpUnit';
    $n->setName($name);
    $result = $n->getName();
    $this->assertEquals($result, $name);
    }

    public function testNodeIp()
    {
        $n = new Node();
        $ip = '1.2.3.4';
        $n->setIp($ip);
        $result = $n->getIp();
        $this->assertEquals($result, $ip);
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
        $status = '1';
        $n->setStatus($status);
        $result = $n->getStatus();
        $this->assertEquals($result, $status);
    }

    public function testNodeCreated()
    {
        $n = new Node();
        $time = "2012-12-20 11:28:14";
        $n->setCreated($time);
        $result = $n->getCreated($time);
        $this->assertEquals($result, $time);
    }
    
    public function testNodeUpdated()
    {
    $n = new Node();
        $time = "2012-12-20 11:28:14";
        $n->setUpdated($time);
        $result = $n->getUpdated($time);
        $this->assertEquals($result, $time);
    }
}
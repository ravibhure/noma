<?php

namespace Noma\NomaBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Noma\NomaBundle\Tests\UnitTestCase;
use Noma\NomaBundle\Entity\Node;
use Noma\NomaBundle\Entity\NodeProp;


class NodeEntityTest extends UnitTestCase
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

    public function testNodeActive()
    {
        $n = new Node();
        $active = '1';
        $n->setActive($active);
        $result = $n->getActive();
        $this->assertEquals($result, $active);
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

    public function testNodeAddNodeProp()
    {
        $n = new Node();
        $np = new NodeProp();
        $n->addNodeProp($np);
        $result = $n->getNodeprops();
        $expect = new ArrayCollection();
        $expect->add($np);
        $this->assertEquals($result, $expect);
    }

    public function testNodeRemoveNodeProp()
    {
        $n = new Node();
        $np = new NodeProp();
        $np2 = new NodeProp();
        $n->addNodeProp($np);
        $n->addNodeProp($np2);

        $n->removeNodeProp($np2);

        $result = $n->getNodeprops();
        $expect = new ArrayCollection();
        $expect->add($np);
        $this->assertEquals($result, $expect);
    }
}

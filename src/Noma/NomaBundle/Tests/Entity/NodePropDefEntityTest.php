<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\NodePropDef;

class NodePropDefEntityTest extends WebTestCase
{
    public function testNodeId()
    {
        $npd = new NodePropDef();
        $result = $npd->getId();
        $this->assertEquals($result, NULL);
    }

    public function testNodePropDefName()
    {
        $npd = new NodePropDef();
        $name = 'phpUnit';
        $npd->setName($name);
        $result = $npd->getName();
        $this->assertEquals($result, $name);
    }

    public function testNodePropDefSingle()
    {
        $npd = new NodePropDef();
        $single = 1;
        $npd->setSingle($single);
        $result = $npd->getSingle();
        $this->assertEquals($result, $single);
        $result = $npd->isSingle();
        $this->assertEquals($result, $single);
    }

    public function testNodePropDefCreated()
    {
        $npd = new NodePropDef();
        $time = "2012-12-20 11:28:14";
        $npd->setCreated($time);
        $result = $npd->getCreated($time);
        $this->assertEquals($result, $time);
    }
    
    public function testNodePropDefUpdated()
    {
        $npd = new NodePropDef();
        $time = "2012-12-20 11:28:14";
        $npd->setUpdated($time);
        $result = $npd->getUpdated($time);
        $this->assertEquals($result, $time);
    }
}

<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\NodeProp;
use Noma\NomaBundle\Entity\NodePropDef;

class NodePropEntityTest extends WebTestCase
{
    public function testNewNodeProp()
    {
        $np = new NodeProp();
        $npd = new NodePropDef();
        $np->setNodePropDef($npd->getId());
        $this->assertEquals($np->getNodePropDef(), $npd->getId());
    }

    public function testNewNodePropContent()
    {
        $np = new NodeProp();
        $content = 'bla';
        $np->setContent($content);
        $this->assertEquals($np->getContent(), $content);
    }

    public function testNodePropCreated()
    {
        $np = new NodeProp();
        $time = "2012-12-20 11:28:14";
        $np->setCreated($time);
        $result = $np->getCreated();
        $this->assertEquals($result, $time);
    }

    public function testNodePropUpdated()
    {
        $np = new NodeProp();
        $time = "2012-12-20 11:28:14";
        $np->setUpdated($time);
        $result = $np->getUpdated();
        $this->assertEquals($result, $time);
    }
}

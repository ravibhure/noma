<?php

namespace Noma\NomaBundle\Tests\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Noma\NomaBundle\Tests\FunctionalTestCase;
use Noma\NomaBundle\Entity\Node;
use Noma\NomaBundle\Entity\NodeProp;
use Noma\NomaBundle\Entity\NodePropDef;

class NodePropEntityTest extends FunctionalTestCase
{
    public function testNewNodeProp()
    {
        $np = new NodeProp();
        $npd = new NodePropDef();
        $np->setNodePropDef($npd->getId());
        $this->assertEquals($np->getNodePropDef(), $npd->getId());
    }

    public function testNodePropId()
    {
        $np = new NodeProp();
        $result = $np->getId();
        $this->assertEquals($result, NULL);
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

    public function testNodePropAddNode()
    {
        $n = new Node();
        $np = new NodeProp();
        $np->addNode($n);
        $result = $np->getNodes();
        $expect = new ArrayCollection();
        $expect->add($n);
        $this->assertEquals($result, $expect);
    }

    public function testNodePropRemoveNode()
    {
        $np = new NodeProp();
        $n = new Node();
        $n2 = new Node();
        $np->addNode($n);
        $np->addNode($n2);

        $np->removeNode($n2);

        $result = $np->getNodes();
        $expect = new ArrayCollection();
        $expect->add($n);
        $this->assertEquals($result, $expect);
    }
}

<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\NodePropDef;

class NodePropDefEntityTest extends WebTestCase
{
    public function testNewNodePropDef()
    {
    $n = new NodePropDef();
    $this->assertEquals(1, 1);
    }
}

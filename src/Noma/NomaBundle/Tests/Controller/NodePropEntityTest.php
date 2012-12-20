<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\NodeProp;

class NodePropEntityTest extends WebTestCase
{
    public function testIndex()
    {
	$n = new NodeProp();
        $this->assertEquals(1,1);
    }
}

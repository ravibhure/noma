<?php

namespace Noma\NomaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Noma\NomaBundle\Entity\Node;

class NodeEntityTest extends WebTestCase
{
    public function testIndex()
    {
	$n = new Node();
        $this->assertEquals(1,1);
    }
}

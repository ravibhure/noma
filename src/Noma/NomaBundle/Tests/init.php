<?php

namespace Noma\NomaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Noma\Noma\DataFixtures\ORM\FixtureLoader;

class FixtureLoaderTest extends WebTestCase
{

    public function getEm()
    {
        return $this->getService( 'doctrine.orm.entity_manager' );
    }

    public function getNode()
    {
        return $this->getEm()->getRepository( 'Noma\NomaBundle\Entity\Node' );
    }

    public function getNodeProp()
    {
        return $this->getEm()->getRepository( 'Noma\NomaBundle\Entity\NodeProp' );
    }

    public function getNodePropDef()
    {
        return $this->getEm()->getRepository( 'Noma\NomaBundle\Entity\NodePropDef' );
    }

    public function loadAllFixtures()
    {
        $loader = new Loader();
        $loader->addFixture( new Node() );
        $loader->addFixture( new NodeProp() );
        $loader->addFixture( new NodePropDef() );
        $this->loadFixtures( $loader );
    }

    public function LoadFixtures( $loader )
    {
        $purger     = new ORMPurger();
        $executor   = new ORMExecutor( $this->getEm(), $purger );
        $executor->execute( $loader->getFixtures() );
    }
    
    public function testDB()
    {
        $this->assertEquals(1, 1);
    }
}
?>
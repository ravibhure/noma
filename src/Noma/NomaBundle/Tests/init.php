<?php

namespace Noma\NomaBundle\Tests;

use Noma\NomaBundle\DependencyInjection\NomaNomaExtension;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Noma\NomaBundle\Entity\Node;
use Noma\NomaBundle\Entity\NodeProp;
use Noma\NomaBundle\Entity\NodePropDef;
use Noma\NomaBundle\DataFixtures\ORM\FixtureLoader;

class FixtureLoaderTest extends WebTestCase
{
    
}
?>
<?php
/**
 * Noma
 *
 * LICENSE
 *
 * This source file is subject to the GPLv3 license that is bundled
 * with this package in the file doc/GPLv3.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @copyright  Copyright (c) 2012 Jochem Kossen <jochem@jkossen.nl>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 */

namespace Noma\NomaBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Noma\NomaBundle\Entity\Node;
use Noma\NomaBundle\Entity\NodeProp;
use Noma\NomaBundle\Entity\NodePropDef;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $nodepropdefs_desc = array(
            array('os', true),
            array('location', true),
            array('service', false));

        $nodepropdefs = array();
        foreach ($nodepropdefs_desc as $n) {
            $nodepropdef = new NodePropDef();
            $nodepropdef->setName($n[0]);
            $nodepropdef->setSingle($n[1]);
            $manager->persist($nodepropdef);
            $nodepropdefs[] = $nodepropdef;
        }

        $nodeprops = array();
        $nodeprops_desc = array(
            array($nodepropdefs[0], 'redhat'),
            array($nodepropdefs[0], 'solaris'),
            array($nodepropdefs[1], 'amsterdam'),
            array($nodepropdefs[1], 'rotterdam'),
            array($nodepropdefs[1], 'zwolle'),
            array($nodepropdefs[2], 'webserver'),
            array($nodepropdefs[2], 'dnsserver'),
            array($nodepropdefs[2], 'ntpserver')
        );

        foreach ($nodeprops_desc as $n) {
            $nodeprop = new NodeProp();
            $nodeprop->setNodePropDef($n[0]);
            $nodeprop->setContent($n[1]);
            $manager->persist($nodeprop);
            $nodeprops[] = $nodeprop;
        }

        $nodes_desc = array(
            array('node01', '10.0.0.1', array($nodeprops[0], $nodeprops[2], $nodeprops[5], $nodeprops[6])),
            array('node02', '10.0.0.2', array($nodeprops[1], $nodeprops[2], $nodeprops[7])),
            array('node03', '10.0.0.3', array($nodeprops[0], $nodeprops[3], $nodeprops[6], $nodeprops[7])),
            array('node04', '10.0.0.4', array($nodeprops[1], $nodeprops[4], $nodeprops[5], $nodeprops[6], $nodeprops[7]))
        );

        foreach ($nodes_desc as $n) {
            $node = new Node();
            $node->setName($n[0]);
            $node->setIp($n[1]);
            $node->setStatus(0);
            foreach ($n[2] as $prop) {
                $node->addNodeProp($prop);
            }

            $manager->persist($node);
        }

        $manager->flush();
    }
}

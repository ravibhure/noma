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

namespace Noma\NomaBundle\DataFixtures\ORM\test;

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
            0 => array('os', true, true),
            1 => array('location', true, true),
            2 => array('service', false, true),
        );

        $nodepropdefs = array();
        foreach ($nodepropdefs_desc as $n) {
            $nodepropdef = new NodePropDef();
            $nodepropdef->setName($n[0]);
            $nodepropdef->setSingle($n[1]);
            $nodepropdef->setActive($n[2]);
            $manager->persist($nodepropdef);
            $nodepropdefs[] = $nodepropdef;
        }

        $oses = array();
        $oses_desc = array(
            'redhat', 'ubuntu', 'gentoo',
            'freebsd', 'solaris');

        $locations = array();
        $locations_desc = array(
            'amsterdam', 'rotterdam', 'zwolle',
            'leeuwarden', 'tilburg', 'groningen');

        $services = array();
        $services_desc = array(
            'webserver', 'dnsserver', 'ntpserver',
            'nfsserver', 'smbserver', 'steppingstone');

        foreach ($oses_desc as $n) {
            $nodeprop = new NodeProp();
            $nodeprop->setNodePropDef($nodepropdefs[0]);
            $nodeprop->setContent($n);
            $manager->persist($nodeprop);
            $oses[] = $nodeprop;
        }

        foreach ($locations_desc as $n) {
            $nodeprop = new NodeProp();
            $nodeprop->setNodePropDef($nodepropdefs[1]);
            $nodeprop->setContent($n);
            $manager->persist($nodeprop);
            $locations[] = $nodeprop;
        }

        foreach ($services_desc as $n) {
            $nodeprop = new NodeProp();
            $nodeprop->setNodePropDef($nodepropdefs[2]);
            $nodeprop->setContent($n);
            $manager->persist($nodeprop);
            $services[] = $nodeprop;
        }

        $nodes_desc = array(
            0 => array('node01', '10.0.0.1', true,
            array($oses[0], $locations[0], $services[0], $services[2])),
            1 => array('node02', '10.0.0.2', true,
            array($oses[1], $locations[2], $services[3], $services[4], $services[5])),
            2 => array('node03', '10.0.0.3', false,
            array($oses[2], $locations[3], $services[1])),
            3 => array('node04', '10.0.0.4', true,
            array($oses[3], $locations[4])),
            4 => array('node05', '10.0.0.5', true,
            array($oses[4], $locations[5], $services[1], $services[2], $services[3], $services[5])),
            5 => array('node06', '10.0.0.6', true,
            array($oses[3], $locations[3], $services[2], $services[3], $services[4])),
            6 => array('node07', '10.0.0.7', true,
            array($oses[1], $locations[2], $services[0], $services[3])),
            7 => array('node08', '10.0.0.8', false,
            array($oses[2], $locations[1], $services[1], $services[2])),
            8 => array('node09', '10.0.0.9', true,
            array($oses[4], $locations[0], $services[3])),
            9 => array('node10', '10.0.0.10', true,
            array($oses[0], $locations[4], $services[2], $services[5]))
        );

        foreach ($nodes_desc as $n) {
            $node = new Node();
            $node->setName($n[0]);
            $node->setIp($n[1]);
            $node->setActive($n[2]);

            foreach ($n[3] as $prop) {
                $node->addNodeProp($prop);
                $prop->addNode($node);
            }

            $manager->persist($node);
        }

        $manager->flush();
    }
}

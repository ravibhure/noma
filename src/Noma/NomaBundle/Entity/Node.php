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
 * @copyright  Copyright (c) 2012-2013 Jochem Kossen <jochem@jkossen.nl>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 */

namespace Noma\NomaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Noma\NomaBundle\Entity\Node
 *
 * @ORM\Table(name="node")
 * @ORM\Entity(repositoryClass="Noma\NomaBundle\Entity\NodeRepository")
 */
class Node extends BaseNomaEntity
{
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=45, unique=true)
     */
    protected $ip;

    /**
     * @var integer $active
     *
     * @ORM\Column(name="active", type="smallint")
     */
    protected $active;

    /**
     * @ORM\ManyToMany(targetEntity="NodeProp", mappedBy="nodes")
     * @ORM\JoinTable(name="node_nodeprop")
     */
    protected $nodeprops;

    public function __construct()
    {
        $this->nodeprops = new ArrayCollection();

        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Node
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Node
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set active
     *
     * @param integer $active
     * @return Node
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return integer
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add Nodeprop
     */
    public function addNodeProp(NodeProp $nodeprop)
    {
        $this->nodeprops->add($nodeprop);
    }

    /**
     * Remove Nodeprop
     */
    public function removeNodeProp(NodeProp $nodeprop)
    {
        $this->nodeprops->removeElement($nodeprop);
    }

    /**
     * Get Nodeprops
     */
    public function getNodeprops()
    {
        return $this->nodeprops;
    }
}

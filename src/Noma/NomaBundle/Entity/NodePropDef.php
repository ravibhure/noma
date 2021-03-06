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
 * Noma\NomaBundle\Entity\NodePropDef
 *
 * @ORM\Table(name="nodepropdef")
 * @ORM\Entity
 */
class NodePropDef extends BaseNomaEntity
{
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var boolean $single
     *
     * @ORM\Column(name="single", type="boolean")
     */
    protected $single;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    /**
     * @ORM\OneToMany(targetEntity="NodeProp", mappedBy="nodepropdef")
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
     * Set single
     *
     * @param string $name
     */
    public function setSingle($single)
    {
        $this->single = $single;
    }

    /**
     * Get single
     *
     * @return boolean
     */
    public function getSingle()
    {
        return $this->single;
    }

    /**
     * Is single?
     *
     * @return boolean
     */
    public function isSingle()
    {
        return $this->getSingle();
    }

    /**
     * Set active
     *
     * @param active $name
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get NodeProps
     *
     * @return NodeProps
     */
    public function getNodeProps()
    {
        return $this->nodeprops;
    }
}

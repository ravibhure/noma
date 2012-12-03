<?php

namespace Noma\NomaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Noma\NomaBundle\Entity\NodePropDef
 *
 * @ORM\Table(name="nodepropdef")
 * @ORM\Entity
 */
class NodePropDef
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * Set created
     *
     * @param \DateTime $created
     * @return Node
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Node
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
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

    /**
     * Invoked before the entity is updated.
     *
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
    }
}
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

namespace Noma\NomaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Noma\NomaBundle\Entity\NodeProp
 *
 * @ORM\Table(name="nodeprop")
 * @ORM\Entity
 */
class NodeProp
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
     *
     * @ORM\ManyToOne(targetEntity="NodePropDef", inversedBy="nodeprops")
     * @ORM\JoinColumn(name="nodepropdef_id", referencedColumnName="id")
     */
    protected $nodepropdef;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    protected $content;

    /**
     * @ORM\ManyToMany(targetEntity="Node", inversedBy="nodeprops")
     * @ORM\JoinTable(name="node_nodeprop")
     */
    protected $nodes;

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

    public function __construct() {
        $this->nodes = new ArrayCollection();

        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nodepropdef
     *
     * @param NodePropDef $nodepropdef
     * @return Node
     */
    public function setNodePropDef($nodepropdef) {
        $this->nodepropdef = $nodepropdef;

        return $this;
    }

    /**
     * Get nodepropdef
     *
     * @return NodePropDef
     */
    public function getNodePropDef() {
        return $this->nodepropdef;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Node
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Add Node
     */
    public function addNode(Node $node) {
        $this->nodes->add($node);
    }

    /**
     * Remove Node
     */
    public function removeNode(Node $node) {
        $this->nodes->removeElement($node);
    }

    /**
     * Get nodes
     *
     * @return ArrayCollection
     */
    public function getNodes() {
        return $this->nodes;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Node
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Node
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * Invoked before the entity is updated.
     *
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->updated = new \DateTime();
    }
}

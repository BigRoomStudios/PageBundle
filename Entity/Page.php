<?php

namespace BRS\PageBundle\Entity;

use BRS\CoreBundle\Core\SuperEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BRS\CoreBundle\Entity\Page
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Page extends SuperEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    public $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @var string $route
     *
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    public $route;

    /**
     * @var string $date_added
     *
     * @ORM\Column(name="date_added", type="date", nullable=true)
     */
    public $date_added;

    /**
     * @var date $date_modified
     *
     * @ORM\Column(name="date_modified", type="date", nullable=true)
     */
    public $date_modified;
	
	/**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="page")
     */
    public $content;

    public function __construct()
    {
        $this->content = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set route
     *
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set date_added
     *
     * @param string $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;
    }

    /**
     * Get date_added
     *
     * @return string 
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set date_modified
     *
     * @param date $dateModified
     */
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;
    }

    /**
     * Get date_modified
     *
     * @return date 
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * Add content
     *
     * @param BRS\PageBundle\Entity\Content $content
     */
    public function addContent(\BRS\PageBundle\Entity\Content $content)
    {
        $this->content[] = $content;
    }

    /**
     * Get content
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContent()
    {
        return $this->content;
    }
}
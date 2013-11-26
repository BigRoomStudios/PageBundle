<?php

namespace BRS\PageBundle\Entity;

use BRS\CoreBundle\Core\SuperEntity;
use BRS\CoreBundle\Core\Utility;
use BRS\FileBundle\Entity\File;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Collections\Criteria;

/**
 * BRS\CoreBundle\Entity\Page
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BRS\PageBundle\Repository\PageRepository")
 * @Gedmo\Tree(type="nested")
 * @ORM\HasLifecycleCallbacks()
 */
class Page extends SuperEntity
{
	/*
	 * name of root folder that holds all entity sub-folders
	 */
    private $root_folder_name = 'Pages';
	
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
     * @var string $class_root
     *
     * @ORM\Column(name="class_root", type="string", length=255, nullable=true, unique = true)
     */
    public $class_root;
	
    /**
     * @var string $description
     * 
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @var string $route
     */
    public $route;

    /**
     * @var string $slug
     *
     * @Gedmo\TreePathSource
	 * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    public $slug;

    /**
     * @var string $template
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    
    public $template;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;
	
	/**
     * @var integer $parent_id
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    public $parent_id;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    public $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent_id")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
    
    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;
	
    /**
     * @var integer $display_order
     *
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    public $display_order;
	
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="updated", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;
	
	/**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="page", cascade={"all"}, orphanRemoval=true)
	 * @ORM\OrderBy({"display_order" = "ASC"})
     */
    public $content;
	
    public function __construct()
    {
        $this->content = new ArrayCollection();
    }
	
	/**
     * @var integer $dir_id
     *
     * @ORM\Column(name="dir_id", type="integer", nullable=TRUE)
     */
    public $dir_id;
	
	/**
	 * @ORM\OneToOne(targetEntity="BRS\FileBundle\Entity\File", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="dir_id", referencedColumnName="id")
	 */
	public $directory;
	
	
	/**
     * Get name of folder to create for this entity  
     *
     * @return string
     */
	public function getFolderName(){
		
		return $this->getTitle();
	}
	
	
	/**
     * Get name of root for this class  
     *
     * @return string
     */
	public function getRootName(){
		
		return $this->root_folder_name;
	}
	
	/**
     * Get driectory
     *
     * @return BRS\FileBundle\Entity\File $dir
     */
    public function getDirectory()
    {
		return $this->directory;

    }
	
	/**
     * Get driectory_id
     *
     * @return BRS\FileBundle\Entity\File $dir
     */
    public function getDirectoryId()
    {
		return $this->dir_id;

    }
	
	/**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
	public function getFiles(){
		return $this->getDirectory()
			->getChildren()
			->matching(Criteria::create()
				->where(Criteria::expr()->isNull("is_dir")));
	}
	
    /**
     * Set directory
     *
     * @param integer $dirId
     */
    public function setDirectory(\BRS\FileBundle\Entity\File $directory)
    {
        $this->dir_id = $directory->getId();
        $this->directory = $directory;
    	
    	return $this;
    }
	
    /**
     * Set dir_id
     *
     * @param integer $dirId
     */
    public function setDirId($dirId)
    {
        $this->dir_id = $dirId;
    	
    	return $this;
    }

    /**
     * Get dir_id
     *
     * @return integer 
     */
    public function getDirId()
    {	
        return $this->dir_id;
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
    	
    	return $this;
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
     * Get lvl
     *
     * @return string
     */
    public function getLvl()
    {
    	return $this->lvl;
    }
    
    /**
     * Get root
     *
     * @return string
     */
    public function getRoot()
    {
    	return $this->root;
    }
    
    /**
     * Get lvl
     *
     * @return string
     */
    public function getDepthTitle()
    {
    	return str_repeat('-', $this->getLvl()).$this->title;
    }
    
    /**
     * Set parent
     *
     * @param $parent
     */
    public function setParent($parent)
    {
 
    	if(is_object($parent) && $parent instanceof Page)
    		if($this->id && $parent->id === $this->id)
    			throw new \Exception('Can not set Self as Parent.  Tried to set Page('.$parent->id.')\'s parent it\'s self');
    		else
    			$this->parent = $parent;
    		
	    		
    	elseif (is_numeric($parent))
    		$this->setParent($this->em->getRepository('BRSPageBundle:Page')->findOneById($parent));
    	
    	else
    		$this->parent = NULL;
    	
    	return $this;
    	
    }

    /**
     * Get parent
     *
     * @return \BRS\PageBundle\Entity\Page
     */
    public function getParent()
    {
		if(is_numeric($this->parent))
			$this->setParent($parent);
		
		return $this->parent;

    }

    /**
     * Get parent_id
     *
     * @return integer
     */
    public function getParentId()
    {
		
		return $this->parent_id;

    }
    
    /**
     * Set children
     *
     * @param Doctrine\Common\Collections\Collection $children
     */
    public function setChildren(\Doctrine\Common\Collections\Collection $children)
    {
    	foreach ($children as $child)
    		$child->setParent($this);
    	
    	// Replace ArrayCollection
    	$this->children = $children;
    	
    	return $this;
    }
    
    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
    	return $this->children;
    }
    
    /**
     * Add children
     *
     * @param \BRS\PageBundle\Entity\Page $child
     */
    public function addChildren(\BRS\PageBundle\Entity\Page $child)
    {
    	$this->children[] = $child;
    	
    	return $this;
    }
    
    /**
     * Set class_root
     * 
     * Can only be done on a root node that is NEW.
     * 
     * @param Object
     */
    public function setClassRoot($object = null)
    {
    	if (empty($object) || !is_object($object))
    		throw new \Exception('$page->setClassRoot($param1) expects parameter 1 to be an object, '.gettype($object).' given.');
    	elseif (!empty($this->id))
    		throw new \Exception('$page->setClassRoot($param1) can only be called on a new Entity.  Tried to call on entity: '.$this->id);
    	
    	$class = get_class($object);
    	
    	$class_title = substr($class, strrpos($class, "\\")+1);
    	
    	switch (substr($class_title,-1)){
    		case 'y':
    			$class_title = substr($class_title,0,-1).'ies';
    			break;
    		case 'h':
    		case 's':
    			$class_title .= 'es';
    			break;
    		default:
    			$class_title .= 's';
    			break;
    	}
    	
    	$this->title = ucfirst($class_title).' Root';
    	$this->class_root = $class;
    	
    	return $this;
    }
    
    /**
     * is class_root
     *
     * Can only be done on a root node that is NEW.
     *
     * @param Object
     */
    public function isClassRoot($object = null)
    {
    	if (empty($object) || !is_object($object))
    		throw new \Exception('$page->setClassRoot($param1) expects parameter 1 to be an object, '.gettype($object).' given.');
    	
    	if (get_class($object) == $this->class_root)
    		return true;
    	return false;
    }
    
    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    	
    	return $this;
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
    	
    	return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set template
     *
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    	
    	return $this;
    }

    /**
     * Get order
     *
     * @return int 
     */
    public function getDisplayOrder()
    {
        return $this->display_order;
    }

    /**
     * Set order
     *
     * @param int $display_order
     */
    public function setDisplayOrder($display_order)
    {
        $this->display_order = $display_order;
    	
    	return $this;
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
    	
    	return $this;
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
    	
    	return $this;
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
    public function addContent(\BRS\PageBundle\Entity\Content $content) {
    	
		$content->setPage($this);
		
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
	

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Page
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
     * @return Page
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
     * Remove content
     *
     * @param \BRS\PageBundle\Entity\Content $content
     */
    public function removeContent(\BRS\PageBundle\Entity\Content $content)
    {
        $this->content->removeElement($content);
    }
}
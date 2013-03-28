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
     * @var string $depth_title
     *
     * @ORM\Column(name="depth_title", type="string", length=255, nullable=true)
     */
    public $depth_title;
	
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
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
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
     * @ORM\OneToMany(targetEntity="Content", mappedBy="page")
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
	 * @ORM\PreRemove
	 */
	public function removeDirectory()
	{
		$dir_id = $this->getDirId();
		
		if($dir_id){
		
			$dir = $this->em->getReference('\BRS\FileBundle\Entity\File', $dir_id);
			
			$this->em->remove($dir);
		}
	}
	
	/**
	 * @ORM\PostUpdate
	 */
	public function updateDirectory()
	{
		
		$dir_id = $this->getDirId();
		
		if($dir_id){
				
			$dir = $this->em->getReference('\BRS\FileBundle\Entity\File', $dir_id);
			
			$folder_name = $this->getFolderName();
		
			$dir->setName($folder_name);
			
			$this->em->persist($dir);
			
		}
		
		//$this->updateNesting();
		
	}
	
	/**
	 * @ORM\PrePersist
	 */
	public function createDirectory()
	{
		
		$dir = new File();
		
		$parent = $this->em->getRepository('BRSFileBundle:File')->getRootByName($this->root_folder_name, TRUE);
		
		if($parent){
		
			$dir->setParent($parent);
			
			$dir->setIsDir(true);

			$folder_name = $this->getFolderName();
			
			$dir->setName($folder_name);
				
			$this->directory = $dir;
			
			$this->em->persist($dir);
			
			$this->em->flush();	// We shouldn't call flush() inside a lifecycle listener...  Heard it was bad, but see no way around this...  :-/
			
			$this->setDirId($dir->id);
			
		}
		
		//$this->path = 'ass';
		
	}
	
	/**
	 * @ORM\PostPersist
	 */
	public function postPersist() {
		$this->updateNesting();
		
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate() {
		$this->updateNesting();
		
	}
	
	/**
	 * 
	 */
	
	public function updateNesting(){
		
		$this->depth_title = str_repeat('-', $this->getLvl()).$this->title;
		
		//die('hello');
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
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
	public function getFiles(){
		
		return $this->getDirectory()->getChildren();
	}
	
    /**
     * Set dir_id
     *
     * @param integer $dirId
     */
    public function setDirId($dirId)
    {
        $this->dir_id = $dirId;
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
 
    	if(is_object($parent) && (get_class($parent) === 'BRS\PageBundle\Entity\Page' || get_class($parent) === 'Proxies\__CG__\BRS\PageBundle\Entity\Page'))
    		if($parent->id === $this->id)
    			throw new \Exception('Can not set Self as Parent.  Tried to set Page('.$parent->id.')\'s parent it\'s self');
    		else
    			$this->parent = $parent;
    		
	    		
    	elseif (is_numeric($parent))
    		$this->setParent($this->em->getRepository('BRSPageBundle:Page')->findOneById($parent));
    	
    	else
    		$this->parent = NULL;
    	
    }

    /**
     * Get parent
     *
     * @return \BRS\PageBundle\Entity\Page
     */
    public function getParent()
    {
		if(is_numeric($parent))
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
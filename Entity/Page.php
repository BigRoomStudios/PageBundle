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
     * @var string $description
     * 
     * @Gedmo\TreePathSource
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @var string $route
     *
	 * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    public $route;

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
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
	
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
	
	/*
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
		
	}
	
	/**
	 * @ORM\PrePersist
	 */
	public function createDirectory()
	{
		
		$dir = new File();
		
		$parent = $this->em->getRepository('BRSFileBundle:File')->getRootByName($this->root_folder_name);
		
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
		
	}
	
	/**
     * Get driectory
     *
     * @return BRS\FileBundle\Entity\File $dir
     */
    public function getDirectory()
    {
    	//Utility::die_dump($this);
		
    	$dir_id = $this->getDirId();
		
		if(!$this->dir_id)
			$this->createDirectory();
		
		return $this->em->getRepository('BRSFileBundle:File')->findOneById($this->dir_id);

    }
	
	/**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection 
     */
	public function getFiles(){
		
		//throw new NotFoundHttpException("getFiles");
		
		$file_repo = $this->em->getRepository('BRSFileBundle:File');
		
		$dir = $this->getDirectory();
		
		$files = $file_repo->children($dir, true);
		
		return $files;
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
     * Set parent
     *
     * @param $parent
     */
    public function setParent($parent)
    {
 
    	if(is_object($parent) && get_class($parent) === 'BRS\PageBundle\Entity\Page')
    		if($parent->id === $this->id)
    			throw new \Exception('Can not set Self as Parent.  Tried to set Page('.$parent->id.')\'s parent it\'s self');
    		else
	    		$this->parent = $parent;
    	
    	elseif (is_numeric($parent))
    		$this->setParent($this->em->getRepository('BRSPageBundle:Page')->findOneById($parent));
    	
    	else
    		$this->parent = NULL;
		
    	$this->em->getRepository('BRSPageBundle:Page')->refresh();
    	
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
	
}
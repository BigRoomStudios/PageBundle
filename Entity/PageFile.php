<?php

namespace BRS\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BRS\PageBundle\Entity\PageFile
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PageFile
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
     * @var integer $page_id
     *
     * @ORM\Column(name="page_id", type="integer")
     */
    public $page_id;

    /**
     * @var integer $file_id
     *
     * @ORM\Column(name="file_id", type="integer")
     */
    public $file_id;

    /**
     * @var integer $display_order
     *
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    public $display_order;
	
	/**
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    public $page;
	
	/**
     * @ORM\ManyToOne(targetEntity="BRS\FileBundle\Entity\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    public $file;
	
	/*
    public function __construct($gallery_id, $file_id)
    {
        $this->gallery_id = $gallery_id;
        $this->file_id = $file_id;
    }
	 * */

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
     * Set page_id
     *
     * @param integer $pageId
     */
    public function setGalleryId($pageId)
    {
        $this->page_id = $pageId;
    }

    /**
     * Get page_id
     *
     * @return integer 
     */
    public function getGalleryId()
    {
        return $this->page_id;
    }

    /**
     * Set page
     *
     * @param \BRS\PageBundle\Entity\Page  $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return \BRS\PageBundle\Entity\Page  
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set file_id
     *
     * @param integer $fileId
     */
    public function setFileId($fileId)
    {
        $this->file_id = $fileId;
    }

    /**
     * Get file_id
     *
     * @return integer 
     */
    public function getFileId()
    {
        return $this->file_id;
    }
	
    /**
     * Set file
     *
     * @param \BRS\FileBundle\Entity\File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return \BRS\FileBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set display_order
     *
     * @param integer $displayOrder
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->display_order = $displayOrder;
    }

    /**
     * Get display_order
     *
     * @return integer 
     */
    public function getDisplayOrder()
    {
        return $this->display_order;
    }

    /**
     * Set page_id
     *
     * @param integer $pageId
     * @return PageFile
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;
    
        return $this;
    }

    /**
     * Get page_id
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->page_id;
    }
}
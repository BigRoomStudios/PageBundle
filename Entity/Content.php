<?php

namespace BRS\PageBundle\Entity;

use BRS\CoreBundle\Core\SuperEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BRS\PageBundle\Entity\Content
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BRS\PageBundle\Repository\ContentRepository")
 */
class Content extends SuperEntity
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
     * @var string $header
     *
     * @ORM\Column(name="header", type="string", length=255, nullable=true)
     */
    public $header;

    /**
     * @var string $sub_header
     *
     * @ORM\Column(name="sub_header", type="string", length=255, nullable=true)
     */
    public $sub_header;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    public $body;

    /**
     * @var string $template
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=true)
     */
    public $template;
	
    /**
     * @var integer $display_order
     *
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    public $display_order;
	
    /**
     * @var integer $page_id
     *
     * @ORM\Column(name="page_id", type="integer")
     */
    public $page_id;

	/**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="content")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    public $page;
	
	
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
     * Set header
     *
     * @param string $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * Get header
     *
     * @return string 
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set sub_header
     *
     * @param string $subHeader
     */
    public function setSubHeader($subHeader)
    {
        $this->sub_header = $subHeader;
    }

    /**
     * Get sub_header
     *
     * @return string 
     */
    public function getSubHeader()
    {
        return $this->sub_header;
    }

    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
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
     * Set page_id
     *
     * @param integer $pageId
     */
    public function setPageId($page_id)
    {
        $this->page_id = $page_id;
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

    /**
     * Set page
     *
     * @param BRS\PageBundle\Entity\Page $page
     */
    public function setPage(\BRS\PageBundle\Entity\Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return BRS\PageBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }
	
	public function setValue($key, $value){
		
		parent::setValue($key, $value);
		
		if($key == 'page_id'){
			
			$page = $this->em->getReference('\BRS\PageBundle\Entity\Page', $value);
			
			$this->setPage($page);
		}
	}
}
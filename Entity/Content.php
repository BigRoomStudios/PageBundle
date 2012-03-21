<?php

namespace BRS\PageBundle\Entity;

use BRS\CoreBundle\Core\SuperEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BRS\CoreBundle\Entity\Content
 *
 * @ORM\Table()
 * @ORM\Entity
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
    private $id;

    /**
     * @var string $header
     *
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;

    /**
     * @var string $sub_header
     *
     * @ORM\Column(name="sub_header", type="string", length=255)
     */
    private $sub_header;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var integer $page_id
     *
     * @ORM\Column(name="page_id", type="integer")
     */
    private $page_id;

	/**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="content")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;
	
	
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
     * Set page_id
     *
     * @param integer $pageId
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;
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
     * @param BRS\CoreBundle\Entity\Page $page
     */
    public function setPage(\BRS\CoreBundle\Entity\Page $page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return BRS\CoreBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }
}
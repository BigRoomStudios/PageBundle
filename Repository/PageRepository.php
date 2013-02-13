<?php

namespace BRS\PageBundle\Repository;

use BRS\CoreBundle\Core\Utility;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Symfony\Component\HttpFoundation\Request;

class PageRepository extends NestedTreeRepository
{
	private $page_collection = array();
	
	public function __construct($em, $class)
	{
		parent::__construct($em, $class);
		$this->page_collection = $this->getEntityManager()
			->createQuery('SELECT p FROM BRSPageBundle:Page p ORDER BY p.display_order ASC')
			->getResult();
	}
	
	public function getNav($route, $page_id)
	{
		$pages = $this->getEntityManager()
			->createQuery('SELECT p FROM BRSPageBundle:Page p ORDER BY p.display_order ASC')
			->getResult();
			
		foreach($pages as $key => $page){
			
			if($page->route == $route){
				
				$pages[$key]->selected = true;
			}
		}
		
		return $pages;
	}
	
	public function get_hierarchy ()
	{
		$hierarchy = array();
		
		foreach( $this->page_collection as $page) {
			//foreach($page->getChildren() as $child)
			//if (count($page->children))
				//Utility::pre_dump($page->children[0]->id);
			
		}
		
		
		
	}
	
	public function get_siblings ($page)
	{
	
	}
	
	public function get_children ($page)
	{
	
	}
	
	public function refresh() {
		
		$em = $this->getEntityManager();
		$repo = $em->getRepository('BRSPageBundle:Page');
		
		
		//print_r('verify '.$repo->verify());
		//Utility::print_pre('recover '.$repo->recover());
		//Utility::print_pre('clear '.$em->clear());
		
	}
	
}
<?php

namespace BRS\PageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
	public function getNav($route)
	{
		$pages = $this->getEntityManager()
			->createQuery('SELECT p FROM BRSPageBundle:Page p ORDER BY p.id ASC')
			->getResult();
			
		foreach($pages as $key => $page){
			
			if($page->route == $route){
				
				$pages[$key]->selected = true;
			}
		}
		
		return $pages;
	}
}
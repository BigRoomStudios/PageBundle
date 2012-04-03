<?php

namespace BRS\PageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContentRepository extends EntityRepository
{
	public function reorder($content)
	{	
		$em = $this->getEntityManager();
		
		$count = 0;
			
		foreach($content as $i => $content_id){
			
			$q = $em->createQuery('update BRSPageBundle:Content c set c.display_order = ?1 where c.id = ?2')
					->setParameter(1, $i)
					->setParameter(2, $content_id);
					
			$count += $q->execute();
		}
		
		return $count;
	}
}
<?php

// src/BRS/Bundle/PageBundle/EventListener/PageListener.php

namespace BRS\PageBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use BRS\CoreBundle\Core\Utility;
use BRS\PageBundle\Repository\PageRepository;
use BRS\PageBundle\Entity\Page;
use BRS\FileBundle\Entity\File;

class PageListener
{	
	
    public function preFlush(\Doctrine\ORM\Event\PreFlushEventArgs $args)
    {

    	$em = $args->getEntityManager();
    	$page_repo = $em->getRepository('BRSPageBundle:Page');
    	$file_repo = $em->getRepository('BRSFileBundle:File');
    	$uow = $em->getUnitOfWork(); 
    	
    	$uow->computeChangeSets();
    	
    	$entities = array_merge(
    			$uow->getScheduledEntityInsertions(),
    			$uow->getScheduledEntityUpdates()
    	);
    	
    	foreach ((array)$entities as $page) {
    		if (!($page instanceof Page))
    			continue;
    		
    		$parent = $page->getParent();
    		
    		if(!$parent) {
    			$parent = $page_repo->getRootFor($page, TRUE);
    			$parent_dir = $parent->getDirectory();
    			if (!$parent_dir)
    				$parent_dir = $file_repo->getRootFor($page, TRUE);
    			$parent->setDirectory($parent_dir);
    			$page->setParent($parent);
    		} else
    			$parent_dir = $parent->getDirectory();
    		
    		$dir = $page->getDirectory();
    		
    		if(!$dir){
    			$dir = new File();
    			$dir->setIsDir(true);
    			$page->setDirectory($dir);
    		}
    		
    		$dir->setName($page->getTitle());
    		$dir->setParent($parent_dir);
    		
    		$uow->recomputeSingleEntityChangeSet(
    			$em->getClassMetadata('BRS\PageBundle\Entity\Page'),
    			$page);
    	}
    }
    
    /**
     * @ORM\PreRemove
     */
    public function preRemove(\Doctrine\ORM\Event\LifecycleEventArgs $event)
    {
    	Utility::log('Calling Delete');
    	
    	$em = $event->getEntityManager();
    	$uow = $em->getUnitOfWork();
    	$entities = $uow->getScheduledEntityDeletions();
    	$page_repo = $em->getRepository('BRSPageBundle:Page');
    	$file_repo = $em->getRepository('BRSFileBundle:File');
    	
    	foreach ((array)$entities as $page) {
    		if (!($page instanceof Page))
    			continue;
    		
    		Utility::log('Deleting a page');
    		
    		$page_repo->removeFromTree($page);
    		
    		$directory = $page->getDirectory();
    		
    		if ($directory) {
    			$children = $directory->getChildren();
    			
    			if($children)
    				foreach ($children as $child)
    					if (!$child->getIsDir())
    						$file_repo->remove($child);
    					elseif ($directory->getParent())
    						$child->setParent($directory->getParent());
    					else
    						$file_repo->remove($child);

    			$file_repo->removeFromTree($directory);
    			$em->remove($directory);
    			
    		}
    		
    	}
    
    }
    
    /**
     * preFlushInsertions
     * 
     * This is the pre-flush handler for Insertions
     * 
     * Note yet tho.
     * 
     */
    
    public function preFlushInsertions(\Doctrine\ORM\Event\PreFlushEventArgs $args)
    {
    	
    }
    
    /**
     * preFlushUpdates
     * 
     * This is the pre-flush handler for Updates
     * 
     * Note yet tho.
     * 
     */
    
    public function preFlushUpdates(\Doctrine\ORM\Event\PreFlushEventArgs $args)
    {
    	
    }
    
    /**
     * preFlushDeletions
     * 
     * This is the pre-flush handler for Deletions
     * 
     * Note yet tho.
     * 
     */
    
    public function preFlushDeletions(\Doctrine\ORM\Event\PreFlushEventArgs $args)
    {
    	
    }
    
    
    
}
?>
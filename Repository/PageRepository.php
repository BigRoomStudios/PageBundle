<?php

namespace BRS\PageBundle\Repository;

use BRS\CoreBundle\Core\Utility;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Symfony\Component\HttpFoundation\Request;
use BRS\PageBundle\Entity\Page;

class PageRepository extends NestedTreeRepository
{
	private $page_collection = array();
	
	public function __construct($em, $class)
	{
		parent::__construct($em, $class);
	}
	
	public function getRootFor($object, $create = FALSE)
	{
		if(!is_object($object)) return false;
		
		$node = new Page();
		
		$class = get_class($object);
		$em = $this->getEntityManager();
		
		$nodes = $em->createQuery("SELECT n FROM BRSPageBundle:Page n WHERE n.class_root = :class_root")
			->setParameter('class_root', $class)
			->setMaxResults(1)
			->getResult();
		
		if(isset($nodes[0]) && $nodes[0] !== null)
			return $nodes[0];
		else if ($create === TRUE)
			return $node->setClassRoot($object);
		else
			return false;
	}
	
	public function getNav($route)
	{	
		if(!is_array($route))
			$route = explode('/', (substr(($route == '/' ? 'home' : $route), -1, 1) === '/' ? substr_replace($route,'',-1) : $route));
		
		array_unshift($route, get_class(new Page()));
		
		$nav = $this->buildTree(null, array('route' => $route));

		return $nav;
	}
	
	public function buildTree(array $nodes = null, array $options = null) {
		$branch = array();
		
		if (!isset($nodes))
			$nodes = $this->getNodesHierarchy($this->getRootFor(new Page()));
		
		$parent_id = !isset($options['parent_id']) ? $this->getRootFor(new Page())->getId() : $options['parent_id'];
		$base = !isset($options['base']) ? '' : $options['base'];
		$route = !isset($options['route']) ? array() : (array) $options['route'];
		$pre_selected = !isset($options['pre_selected']) ? true : $options['pre_selected'];
		
		while ($node = array_shift($nodes)) {
			if ($node['parent_id'] == $parent_id) {
				
				$node['selected'] = ($pre_selected && @$route[$node['lvl']] == $node['slug']) ? true : false;
				$node['route'] = $base . $node['slug'];
				
				$children = $this->buildTree($nodes, array(
						'parent_id' => $node['id'],
						'base' => $node['route'].'/',
						'route' => $route,
						'pre_selected' => $node['selected']));
				
				if ($children) {
					$node['children'] = $children;
				}
				
				$branch[$node['id']] = $node;
			}
		}
	
		return $branch;
	}

	public function findOneByRoute($route)
	{
		$route = explode('/', (substr(($route == '/' ? 'home' : $route), -1, 1) === '/' ? substr_replace($route,'',-1) : $route));
		
		$from[] = 'BRSPageBundle:Page r';
		$where[] = 'r.class_root = \''.get_class(new Page()).'\'';
		$where[] = 'r.id = p'.(count($route)-1).'.parent_id';
		foreach(array_reverse($route) as $lvl => $val) {
			$from[] = 'BRSPageBundle:Page p'.$lvl;
			$where[] = 'p'.$lvl .'.slug = \''.$val.'\'';
			$where[] = 'p'.$lvl .'.lvl = '.(count($route)-($lvl));
			if ($lvl > 0)
				$where [] = 'p' . $lvl .'.id = p' . ($lvl-1) .'.parent_id';
		}
		
		$page = $this->getEntityManager()
			->createQuery('SELECT p0 FROM ' . implode(', ', $from) . ' WHERE ' . implode(' AND ', $where))
			->getResult();
		
		return array_shift($page);
		
	}
	
	/**
	 * 
	 * DOES NOT WORK, MF...  Some day...
	 */
	
	public function getDropDownList(){
		
		$list = Array();
		
		$pages = $this->getEntityManager()
			->createQuery('SELECT p FROM BRSPageBundle:Page p ORDER BY p.root ASC, p.lft ASC')
			->getResult();
		
		return $pages;
	}
	
}
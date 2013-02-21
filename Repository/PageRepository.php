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
		//$this->page_collection = $this->childrenHierarchy();
	}
	
	public function getNav(array $route)
	{	
		$nav = $this->buildTree(null, array('route' => $route));

		return $nav;
	}
	
	public function buildTree(array $nodes = null, array $options = null) {
		$branch = array();
		
		if (!isset($nodes))
			$nodes = $this->getNodesHierarchy();
		
		$parent_id = !isset($options['parent_id']) ? '' : $options['parent_id'];
		$base = !isset($options['base']) ? '' : $options['base'];
		$route = !isset($options['route']) ? array() : (array) $options['route'];
		$pre_selected = !isset($options['pre_selected']) ? true : $options['pre_selected'];
		
		
		while ($node = array_shift($nodes)) {
			if ($node['parent_id'] == $parent_id) {
				
				$node['selected'] = ($pre_selected && @$route[$node['lvl']] == $node['route']) ? true : false;
				$node['route'] = $base . $node['route'] . '/';
				
				$children = $this->buildTree($nodes, array(
						'parent_id' => $node['id'],
						'base' => $node['route'],
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

	public function findOneByRoute(array $route)
	{
		
		foreach(array_reverse($route) as $lvl => $val) {
			$from[] = 'BRSPageBundle:Page p'.$lvl;
			$where[] = 'p'.$lvl .'.route = \''.$val.'\'';
			$where[] = 'p'.$lvl .'.lvl = '.(count($route)-($lvl+1));
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
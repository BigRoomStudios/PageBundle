<?php

namespace BRS\PageBundle\Controller;

use BRS\CoreBundle\Core\WidgetController;
use BRS\CoreBundle\Core\Utility as BRS;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PageController handles front-end dynamic page rendering
 */
class PageController extends WidgetController
{
	
	protected function getVars($route){
		$nav = $this->getNav($route);
		
		$page = $this->lookupPage($route);
		
		if(!is_object($page)){
			
			throw $this->createNotFoundException('This is not the page you\'re looking for...');
		}
		
		$rendered = $this->renderPage($page);
		
		if($this->isAjax()){
			
			$page_values = array(
				'title' => $page->title,
				'route' => $page->route,
				'id' => $page->id,
			);
			
			$values = array(
				'page' => $page_values,
				'rendered' => $rendered,
			);
		
			return $values;		
		}
		
		$vars = array(
			'route' => $route,
			'page' => $page,
			'rendered' => $rendered,
			'nav' => $nav,
		);
			
		return $vars;
	}
	
	
	protected function renderPage($page){
		
		$content = $page->getContent();
		
		$rendered_content = array();
		
		foreach($content as $content_block){
			
			$rendered_content[] = $this->renderContent($content_block);
		}
		
		$vars = array(
			'page' => $page,
			'content' => $rendered_content,
		);
		
		$template = ($page->template) ? $page->template : 'BRSPageBundle:Page:standard.html.twig';
		
		$rendered = $this->container->get('templating')->render($template, $vars);
		
		return $rendered;	
	}
	
	protected function renderContent($content){
		
		$vars = array(
		
			'content' => $content,
		);
		
		$template = ($content->template) ? $content->template : 'BRSPageBundle:Content:default.html.twig';
		
		$rendered = $this->container->get('templating')->render($template, $vars);
		
		return $rendered;		
	}
	
	protected function lookupPage($route){
		
		$page = $this->getRepository('BRSPageBundle:Page')->findOneByRoute($route);
		
		return $page;
	}
	
	protected function getNav($route){
		
		$pages = $this->getRepository('BRSPageBundle:Page')->getNav($route);
		
		return $pages;
	}
	
}
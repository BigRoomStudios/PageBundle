<?php

namespace BRS\PageBundle\Controller;

use BRS\CoreBundle\Core\WidgetController;
use BRS\CoreBundle\Core\Utility as BRS;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * PageController handles front-end dynamic page rendering
 */
class PageController extends WidgetController
{
	
	protected function getVars($route, $form){
		
		$nav = $this->getNav($route);
		
		$page = $this->lookupPage($route);
		
		if(!is_object($page)){
			
			throw $this->createNotFoundException('This is not the page you\'re looking for...');
		}
		
		$rendered = $this->renderPage($page, $form);
		
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
	
	
	protected function renderPage($page, $form) {
		
		$content = $page->getContent();
		
		$rendered_content = array();
		
		foreach($content as $content_block){
			
			$rendered_content[] = $this->renderContent($content_block, $form);
		}
		
		$vars = array(
			'page' => $page,
			'content' => $rendered_content,
			'register_form' => $form,
		);
		
		$template = ($page->template) ? $page->template : 'BRSPageBundle:Page:standard.html.twig';
		
		$rendered = $this->container->get('templating')->render($template, $vars);
		
		return $rendered;	
	}
	
	protected function renderContent($content, $form){
		
		//I had to set it on content because passing in a separate template var wasn't working...giving up on figuring out why for now and moving on...
		$content->register_form = $form;
		
		$vars = array(
			'content' => $content,
			'register_form' => $form, //this is not getting passed into the template?!?!?!?!?!
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
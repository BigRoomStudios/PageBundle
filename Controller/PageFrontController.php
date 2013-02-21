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
 * @Route("")
 */
class PageFrontController extends PageController
{
	/**
	 * render inner page content for a given dynamic route
	 *
	 * @Route("/render/{route}", requirements={"route" = ".*"})
	 * 
	 */
	public function renderAction($route)
	{
		$page = $this->lookupPage($route);
		
		if(!is_object($page)){
			
			throw $this->createNotFoundException('This is not the page you\'re looking for...');
		}
		
		$rendered = $this->renderPage($page);
		
		if($this->isAjax()){
			
			$values = array(
				'rendered' => $rendered,
			);
		
			return $this->jsonResponse($values);
				
		}else{
			
			return new Response($rendered);
		}
	}
	
	/**
	 * render content block for a given id
	 *
	 * @Route("/content/{id}")
	 * 
	 */
	public function contentAction($id)
	{
		$content = $this->getRepository('BRSPageBundle:Content')->findOneById($id);
		
		$rendered = $this->renderContent($content);
		
		if($this->isAjax()){
			
			$values = array(
				'rendered' => $rendered,
			);
		
			return $this->jsonResponse($values);
				
		}else{
			
			return new Response($rendered);
		}
	}
	
	/**
	 * Displays the home page
	 *
	 * @Route("/")
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function indexAction()
	{	
		
		return $this->pageAction('home');
		
		/*$nav = $this->getNav('home');
					
		$page = $this->lookupPage('home');
		
		if(!is_object($page)){
			
			return $this->render('BRSFrontBundle:Default:index.html.twig', array('title' => ''));
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
		
			return $this->jsonResponse($values);		
		}
		
		$vars = array(
			'page' => $page,
			'rendered' => $rendered,
			'nav' => $nav,
		);
			
		return $vars;*/
	}
	
	/**
	 * display page content for a given dynamic route
	 *
	 * @Route("/{route}", requirements={"route" = ".+"})
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function pageAction($route)
	{
		$route = explode('/', (substr(($route == '/' ? 'home' : $route), -1, 1) === '/' ? substr_replace($route,'',-1) : $route));
		
		$vars = $this->getVars($route);
	
		//BRS::die_pre($vars['page']->content);	
		
		if($this->isAjax()){
			
			return $this->jsonResponse($vars);		
		}
		
		$template = ($vars['page']->template) ? $vars['page']->template : 'BRSPageBundle:Page:default.html.twig';
		$rendered = $this->container->get('templating')->render($template, $vars);
		
		return new Response($rendered);
	}
	
}

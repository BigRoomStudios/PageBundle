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
		
		if($this->isAjax()){
			
			return $this->jsonResponse($vars);		
		}
		
		return $vars;
	}
	
}

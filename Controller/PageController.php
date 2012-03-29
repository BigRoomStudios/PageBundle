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
class PageController extends WidgetController
{

	/**
	 * Displays a form to create a new entity for this admin module
	 *
	 * @Route("/")
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function indexAction()
	{
		$nav = $this->getNav('home');
					
		$page = $this->lookupPage('home');
		
		if(!is_object($page)){
			
			return $this->render('BRSFrontBundle:Default:index.html.twig', array('title' => 'Hello World!'));
		}
			
		$vars = array(
			'page' => $page,
			'nav' => $nav,
		);
			
		return $vars;
	}
	
	
	/**
	 * display page content for a given dynamic route
	 *
	 * @Route("/{route}", requirements={"route" = ".*"})
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function pageAction($route)
	{
		$nav = $this->getNav($route);
		
		$page = $this->lookupPage($route);
		
		if(!is_object($page)){
			
			throw $this->createNotFoundException('This is not the page you\'re looking for...');
		}
				
		$vars = array(
			'route' => $route,
			'page' => $page,
			'nav' => $nav,
		);
			
		return $vars;
	}
	
	protected function lookupPage($route)
	{
		$page = $this->getRepository('BRSPageBundle:Page')->findOneByRoute($route);
		
		return $page;
	}
	
	protected function getNav($route){
		
		$pages = $this->getRepository('BRSPageBundle:Page')->getNav($route);
		
		return $pages;
		
	}
	
}

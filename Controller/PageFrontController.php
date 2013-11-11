<?php

namespace BRS\PageBundle\Controller;

use BRS\CoreBundle\Core\WidgetController;
use BRS\CoreBundle\Core\Utility as BRS;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
	 * @Route("/", name="page_home")
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function indexAction(Request $request) {	
		
		return $this->pageAction($request, 'home');
	}
	
	/**
	 * display page content for a given dynamic route
	 *
	 * @Route("/{route}", requirements={"route" = ".+"}, name="page")
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function pageAction(Request $request, $route) {
		
		$register_form = null;
		
		//add in the registration form if rendering the home page.  This needs to be generalized
		if($route == 'home') {
			
			//get the form
			$register_form = $this->get_register_form($request);
			
			//create the form view
			$register_form = $register_form->createView();
			
		}
		
		$vars = $this->getVars($route, $register_form);
		
		if($this->isAjax()){
			
			return $this->jsonResponse($vars);
		}
		
		return $vars;
	}
	
	/**
	 * 
	 */
	public function get_register_form(Request $request) {
		
		$formFactory = $this->container->get('fos_user.registration.form.factory');
		$userManager = $this->container->get('fos_user.user_manager');
		
		$user = $userManager->createUser();
		$user->setEnabled(true);
		
		$form = $formFactory->createForm();
		
		return $form;
		
	}
	
}

<?php

namespace BRS\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//use BRS\CoreBundle\Core\WidgetController;
//use BRS\CoreBundle\Core\Utility as BRS;

//use Symfony\Component\HttpFoundation\Response;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * 
 */
class PageFrontController extends Controller
{
	
	/**
	 * Displays the home page
	 */
	public function homeAction() {
		
		return $this->pageAction('home');
		
	}
	
	/**
	 * Generates a page view.  If a page record is not found then a 404 error is returned.
	 * 
	 * @param page_name - name of the page
	 */
	public function pageAction($page_name) {
		
		//get the page entity
		if($page = $this->getDoctrine()->getRepository('BRSPageBundle:Page')->findOneByRoute($page_name)) {
			
			//generate page parameters
			$page_values = array(
				'page' => $page,
				'content' >= $page->getContent(),
			);
			
			//pull the template from the page...fall back to the default value
			$page_template = $page->getTemplate() ? $page->getTemplate() : 'BRSPageBundle:Page:default.html.twig';
			
			//render the template
			return $this->render($page_template, $page_values);
			
		}
		else {
			
			//page not found...issue 404 error
			throw $this->createNotFoundException('The page does not exist');
			
		}
		
		//$vars = $this->getVars($route);
		
		if($this->isAjax()) {
			
			return $this->jsonResponse($vars);		
		}
		
		return $vars;
		
		//return $this->render('AppMainBundle:Page:home.html.twig');
		
	}
	
	/**
	 * render inner page content for a given dynamic route
	 *
	 */
	// public function renderAction($route)
	// {
// 		
		// $page = $this->lookupPage($route);
// 		
		// if(!is_object($page)){
// 			
			// throw $this->createNotFoundException('This is not the page you\'re looking for...');
		// }
// 		
		// $rendered = $this->renderPage($page);
// 		
		// if($this->isAjax()){
// 			
			// $values = array(
				// 'rendered' => $rendered,
			// );
// 		
			// return $this->jsonResponse($values);
// 				
		// }else{
// 			
			// return new Response($rendered);
		// }
	// }
	
	/**
	 * render content block for a given id
	 *
	 */
	// public function contentAction($id)
	// {
		// $content = $this->getRepository('BRSPageBundle:Content')->findOneById($id);
// 		
		// $rendered = $this->renderContent($content);
// 		
		// if($this->isAjax()){
// 			
			// $values = array(
				// 'rendered' => $rendered,
			// );
// 		
			// return $this->jsonResponse($values);
// 				
		// }else{
// 			
			// return new Response($rendered);
		// }
	// }
	
}

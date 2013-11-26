<?php

namespace BRS\PageBundle\Controller;

use BRS\CoreBundle\Core\Utility as BRS;
use BRS\PageBundle\Form\Type\PageType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * PageController handles front-end dynamic page rendering
 */
class EditPageController extends Controller
{
	
	/**
	 * 
	 */
	public function editAction(Request $request, $route) {
		
		$page = $this->getDoctrine()->getRepository('BRSPageBundle:Page')->findOneByRoute($route);
		
		//create a new edit form for this page
		$form = $this->createForm(new PageType(), $page);
		
		//if they posted any data
		if($request->isMethod('POST')) {
			
			//bind the request to the form
			$form->bind($request);
			
			//if the form is valid
			if($form->isValid()) {
				
				//$page = $form->getData();
				
				//persist the page
				$em = $this->getDoctrine()->getManager();
				$em->persist($page);
				$em->flush();
				
			} else {
				
				//get the errors
				//print_r($form->getErrors());
				//die();
			}
			
		}
		
		$twig_vars = array(
			'edit_form' => $form->createView(),
		);
		
        return $this->render('BRSPageBundle:Page:edit.html.twig', $twig_vars);
		
	}
	
	/*protected function getVars($route){
		
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
	}*/
	
}
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
			'page' => array(
				'slug' => 'admin',
			),
		);
		
        return $this->render('BRSPageBundle:Page:edit.html.twig', $twig_vars);
		
	}
	
}

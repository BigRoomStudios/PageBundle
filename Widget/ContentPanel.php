<?php

namespace BRS\PageBundle\Widget;

use BRS\CoreBundle\Core\Widget\PanelWidget;
use BRS\CoreBundle\Core\Utility;

use BRS\PageBundle\Entity\Content;
use BRS\PageBundle\Entity\Page;
use BRS\PageBundle\Widget\ContentForm;

/**
 * Content panel lets you add multiple content blocks to a page
 */
class ContentPanel extends PanelWidget
{
	
	protected $template = 'BRSPageBundle:Widget:content.panel.html.twig';
	
	protected $page_widget;
	
	protected $content_form;
	
	protected $page;
	
	public function setup(){
			
		$content_form = new ContentForm();
		$content_form->setEntity(new Content());
		
		$this->content_form = $this->addWidget($content_form, 'add_content');
		
		$this->setWidgets(array(
			'content_form' =>& $this->content_form,
		));
	}
	
	public function setPageWidget(&$page_widget){
		
		$this->page_widget =& $page_widget;
		
		$page_widget->addListener($this, 'edit.save', 'onPageUpdate');
		$page_widget->addListener($this, 'get.entity', 'onPageUpdate');
	}
	
	public function onPageUpdate($event){
		
		$this->page = $event->entity;
		
		$content = new Content();
		
		$content->setPageId($this->page->getId());
		
		$this->content_form->setEntity($content);
	}
	
	public function getVars($render = true){
		
		$add_vars = array(
			'page' => $this->page,
		);
		
		$vars = array_merge(parent::getVars(), $add_vars);
		
		return $vars;
	}
	
	
	/**
	 * get a set of rendered rows
	 *
	 * @Route("/content")
	 */
	public function contentAction()
	{	
		$view = 'BRSPageBundle:Widget:content.list.html.twig';
		
		$request = $this->getRequest();	
			
		$page_id = $request->query->get('page_id');
		
		if($page_id){
			
			$this->page = $this->getRepository()->find($page_id);
		}
		
		$vars = array();
		
		$vars['page'] = $this->page;
		$vars['count'] = count($this->page->getContent());
		
		if($this->isAjax()){
			
			$vars['rendered'] = $this->container->get('templating')->render($view, $vars);
			
			unset($vars['page']);
			
			return $this->jsonResponse($vars);
				
		}else{
			
			$response = new Response();
			
			return $this->container->get('templating')->renderResponse($view, $vars, $response);
		}
	}
	
	
	/**
	 * get a set of rendered rows
	 *
	 * @Route("/delete")
	 */
	public function deleteAction()
	{	
		$request = $this->getRequest();	
			
		$content_id = $request->query->get('id');
		
		if($content_id){
			
			$content = $this->getRepository('BRSPageBundle:Content')->find($content_id);
			
			if($content){
				
				$em = $this->getEntityManager();
				
				$em->remove($content);
				
				$em->flush();
				
				$success = true;
			}
		}
		
		$vars = array('success' => $success);
			
		return $this->jsonResponse($vars);
	}
	
}
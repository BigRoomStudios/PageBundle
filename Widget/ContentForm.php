<?php

namespace BRS\PageBundle\Widget;

use BRS\CoreBundle\Core\Widget\EditFormWidget;
use BRS\CoreBundle\Core\Widget\FormWidget;
use BRS\CoreBundle\Core\Utility;

use BRS\PageBundle\Entity\Content;
use BRS\PageBundle\Entity\Page;

/**
 * Conten form widget
 */
class ContentForm extends EditFormWidget
{
		
	protected $page_id;
	
	protected $page_widget;
	
	protected $class = 'content-form-widget no-labels stacked hidden';
	
	protected $actions = array(
		
		'save' => array(
			'type' => 'button',
		),
		
		'cancel' => array(
			'type' => 'button',
			'class' => 'button-grey',
		),
	);
	
	public function __construct()
	{
		parent::__construct();	
		
		$this->setEntityName('BRSPageBundle:Content');
		$this->setEntity(new Content());
		
		$edit_fields = array(
			
			'page_id' => array(
				'type' => 'hidden',
			),
			
			'header' => array(
				'type' => 'text',
				'attr' => array(
					'class' => 'extra-large',
					'placeholder' => 'Content Title',
				),
			),
	
			'body' => array(
				'type' => 'textarea',
				'required' => true,
				'attr' => array(
					'class' => 'extra-large',
					'placeholder' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
				),
			),
			
			'template' => array(
				'type' => 'text',
				'attr' => array(
					'class' => 'extra-large',
					'placeholder' => 'Template e.g. BundleName:file.html.twig',
				),
			),
		);
		
		$this->setFields($edit_fields);
	}
	
	/*
	public function setPageWidget(&$page_widget){
		
		$this->page_widget =& $page_widget;
		
		$page_widget->addListener($this, 'edit.save', 'onPageSave');
		$page_widget->addListener($this, 'get.id', 'onGetById');
	}
	
	public function onPageSave($event){
		
		$page = $this->page_widget->getEntity();
		
		$page_id = $page->getId();
		
		$this->page_id = $page_id;
		
		//$this->sessionSet('page_id', $page_id);
	}
	
	public function onGetById($event){
		
		$this->page_id = $event->id;
	}
	
	public function & getEntity(){
		
		$entity =& parent::getEntity();
		
		$entity->setPageId($this->page_id);
		
		return $entity;
	}
	
	/*public function getVars($render = true){
		
		$add_vars = array(
			'class' => '',
		);
		
		$vars = array_merge(parent::getVars(), $add_vars);
		
		return $vars;
	}*/
	
	
}
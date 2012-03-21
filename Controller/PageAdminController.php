<?php

namespace BRS\PageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BRS\CoreBundle\Core\Widget\ListWidget;
use BRS\CoreBundle\Core\Widget\EditFormWidget;
use BRS\AdminBundle\Controller\AdminController;
use BRS\PageBundle\Entity\Page;

/**
 * Member controller.
 *
 * @Route("/page")
 */
class PageAdminController extends AdminController
{
		
	protected function setup()
	{
		parent::setup();
				
		$this->setRouteName('brs_page_pageadmin');
		$this->setEntityName('BRSPageBundle:Page');
		$this->setEntity(new Page());
		
		$list_fields = array(
			'edit' => array(
				'type' => 'link',
				'route' => array(
					'name' => 'brs_page_pageadmin_edit',
					'params' => array('id'),
				),
				'label' => 'edit',
				'width' => 100,
				'nonentity' => true,
			),
			'title' => array(
				'type' => 'text',
			),
			'route' => array(
				'type' => 'text',
			),
			
			'description' => array(
				'type' => 'text',
			),
		);
		
		$list_widget = new ListWidget();
		$list_widget->setListFields($list_fields);
		$this->addWidget($list_widget, 'list_pages');
	
		$edit_fields = array(
			
			'config' => array(
			
				'type' => 'group',
				'fields' => array(
			
					'title' => array(
						'type' => 'text',
						'required' => true,
						'attr' => array(
							'class' => 'valid-alpha'
						)
					),
					
					'route' => array(
						'type' => 'text',
						'required' => true,
						'attr' => array(
							'class' => 'valid-route'
						)
					),
					
				),
			),
			
			'details' => array(
			
				'type' => 'group',
				'fields' => array(
			
					'description' => array(
						'type' => 'textarea',
						'required' => false,
						'attr' => array(
							'class' => ''
						),
					),
				),
			),	
		);
		
	
/*		$edit_fields = array(
			'title' => array(
				'type' => 'text',
			),
			'route' => array(
				'type' => 'text',
			),
			'description' => array(
				'type' => 'textarea',
			),
		);
*/		
		$edit_widget = new EditFormWidget();
		$edit_widget->setFields($edit_fields);
		$edit_widget->setSuccessRoute('brs_page_pageadmin_edit');
		$this->addWidget($edit_widget, 'edit_page');
		
		$this->addView('index', $list_widget);
		$this->addView('new', $edit_widget);
		$this->addView('edit', $edit_widget);
	}
	
}

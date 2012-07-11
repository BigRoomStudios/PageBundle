<?php

namespace BRS\PageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BRS\CoreBundle\Core\Widget\ListWidget;
use BRS\CoreBundle\Core\Widget\EditFormWidget;
use BRS\CoreBundle\Core\Widget\PanelWidget;
use BRS\AdminBundle\Controller\AdminController;
use BRS\PageBundle\Entity\Page;
use BRS\PageBundle\Entity\Content;
use BRS\PageBundle\Widget\ContentPanel;

/**
 * Member controller.
 *
 * @Route("/admin/page")
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
				'nav' => true,
				'label' => 'edit',
				'width' => 55,
				'nonentity' => true,
				'class' => 'btn btn-mini',
			),
			/*'display_order' => array(
				'type' => 'text',
			),*/
			'title' => array(
				'type' => 'text',
			),
			'route' => array(
				'type' => 'text',
			),
		);
		
		$list_widget = new ListWidget();
		$list_widget->setListFields($list_fields);
		$list_widget->setReorderField('display_order');
		
		$this->addWidget($list_widget, 'list_pages');
	
		$edit_fields = array(
		
			'title' => array(
				'type' => 'text',
				'required' => true,
			),
			
			'route' => array(
				'type' => 'text',
				'required' => true,
				'attr' => array(
					'class' => 'valid-route'
				)
			),
			
			'template' => array(
				'type' => 'text',
			),
		);
		
		$page_widget = new EditFormWidget();
		$page_widget->setFields($edit_fields);
		$page_widget->setSuccessRoute('brs_page_pageadmin_edit');
		$this->addWidget($page_widget, 'edit_page');
		
		$content_panel = new ContentPanel();
		$content_panel->setPageWidget($page_widget);
		
		$this->addWidget($content_panel, 'content_panel');
		
		$edit_panel = new PanelWidget();
		$this->addWidget($edit_panel, 'edit_panel');
		$edit_panel->addListener($page_widget, 'get.id', 'onParentGetById');
		
		$edit_panel->setWidgets(array(
			'page' => &$page_widget,
			'content' => &$content_panel,
		));
		
		
		$this->addView('index', $list_widget);
		$this->addView('new', $page_widget);
		$this->addView('edit', $edit_panel);
	}
	
}
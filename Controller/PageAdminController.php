<?php

namespace BRS\PageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BRS\CoreBundle\Core\Widget\ListWidget;
use BRS\CoreBundle\Core\Widget\EditFormWidget;
use BRS\CoreBundle\Core\Widget\PanelWidget;
use BRS\CoreBundle\Core\Utility;
use BRS\AdminBundle\Controller\AdminController;
use BRS\PageBundle\Entity\Page;
use BRS\PageBundle\Entity\Content;
use BRS\PageBundle\Widget\ContentPanel;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

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
			'up' => array(
					'type' => 'link',
					'route' => array(
							'name' => 'brs_page_pageadmin_up',
							'params' => array('id'),
					),
					'nav' => true,
					'label' => 'up',
					'width' => 55,
					'nonentity' => true,
					'class' => 'btn btn-mini rdr-up',
			),
			'down' => array(
					'type' => 'link',
					'route' => array(
							'name' => 'brs_page_pageadmin_down',
							'params' => array('id'),
					),
					'nav' => true,
					'label' => 'down',
					'width' => 55,
					'nonentity' => true,
					'class' => 'btn btn-mini rdr-dwn',
			),
			/*'display_order' => array(
				'type' => 'text',
			),*/
			'title' => array(
				'label' => 'title',
				'type' => 'text',
			)
		);
		
		$list_widget = new ListWidget();
		$list_widget->setListFields($list_fields);
		//$list_widget->setReorderField('display_order');
		
		$this->addWidget($list_widget, 'list_pages');
		$list_widget->setOrderBy(array('root'=>'ASC','lft'=>'ASC'));
		//$list_widget->setFilters();
	
		$edit_fields = array(
		
			'title' => array(
				'type' => 'text',
				'required' => true,
			),
			
			'template' => array(
				'type' => 'text',
			),
			
			'parent' => array(
				'type' => 'entity',
				'options' => array(
					'label' => 'Parent',
					'class' => 'BRSPageBundle:Page',
					'property' => 'title',
					'empty_value' => 'No Parent Page',
					'empty_data' => null,
					'by_reference' => TRUE,
					'query_builder' => function($er) {
						return $er->createQueryBuilder('p')
							->from('BRS\PageBundle\Entity\Page', 'r')
							->orderBy('p.lft', 'ASC')
							->andwhere('p.lft BETWEEN r.lft AND r.rgt')
							->andWhere('p.id != r.id')
							->set('r.class_root', 'BRS\PageBundle\Entity\Page');
					},
				),
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
	
	/**
	 * Displays a form to create a new entity for this admin module
	 *
	 * @Route("/{id}/up")
	 */
	function up($id){
		$repo = $this->getRepository('BRSPageBundle:Page');
		$page = $repo->find($id);
		if ($page->getRoot() != $page->getId())
			$repo->moveUp($page,1);
		//else
		//		alert
		
		$view = $this->getView('index');
		
		$view->handleRequest();
		
		$values = array(
		
				'view' => $view->render(),
		);
		
		if($this->isAjax()){
				
			return $this->jsonResponse($values);
		}
		
		$values = array_merge( $this->getViewValues(), $values );
		
		return $values;
	}
	
	/**
	 * Displays a form to create a new entity for this admin module
	 *
	 * @Route("/{id}/down")
	 */
	function down($id){
		$repo = $this->getRepository('BRSPageBundle:Page');
		$page = $repo->find($id);
		if ($page->getRoot() != $page->getId())
			$repo->moveDown($page,1);
		
		$view = $this->getView('index');
		
		$view->handleRequest();
		
		$values = array(
		
				'view' => $view->render(),
		);
		
		if($this->isAjax()){
				
			return $this->jsonResponse($values);
		}
		
		$values = array_merge( $this->getViewValues(), $values );
		
		return $values;
	}
}
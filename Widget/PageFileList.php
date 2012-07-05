<?php

namespace BRS\PageBundle\Widget;

use BRS\FileBundle\Widget\FileList;
use BRS\FileBundle\Entity\File;
use BRS\CoreBundle\Core\Utility;
use BRS\PageBundle\Entity\PageFile;


/**
 * GalleryFile list widget
 * 
 */
class PageFileList extends FileList
{
		
	protected $template = 'BRSFileBundle:Widget:file.list.html.twig';
		
	public function __construct()
	{
		parent::__construct();	
		
		$this->setEntityName('BRSPageBundle:PageFile');
		$this->addJoin('p.file', 'f');
		
		$list_fields = array(
	
			'edit' => array(
				'type' => 'link',
				'route' => array(
					'name' => 'brs_file_fileadmin_edit',
					'params' => array('id' => 'file_id'),
				),
				'nav' => true,
				'label' => 'edit',
				'width' => 120,
				'nonentity' => true,
				'class' => 'btn btn-mini',
			),
			
			'thumb' => array(
				'type' => 'thumbnail',
				'width' => 55,
				'nonentity' => true,
				'file_id_field' => 'file_id',
			),
			
			'name' => array(
				'alias' => 'f',
				'type' => 'link',
				'route' => array(
					'name' => 'brs_file_file_download',
					'params' => array('id' => 'file_id'),
				),
			),
			
			'file_id' => array(
				'type' => 'hidden',
			),
			
			'type' => array(
				'alias' => 'f',
				'type' => 'text',
			),
			
			'title' => array(
				'alias' => 'f',
				'type' => 'text',
			),
		);
		
		$this->setListFields($list_fields);
	}
	
	public function uploadAction(){
			
		$form = $this->getFileUploadForm();
		
		$request = $this->getRequest();
		
		$file_repo = $this->getRepository('BRSFileBundle:File');
		
		$values = $file_repo->hanldeUploadRequest($request, $form);
		
		if(isset($values['file'])){
		
			$file = $values['file'];
			
			$page_id = $this->sessionGet('page_id');
			
			//print('here' . $gallery_id);
			
			//Utility::die_pre($file);
			
			$em = $this->getEntityManager();
			
			$page_file = new PageFile();
			
			$page_file->setFile($file);
			
			$page = $em->getReference('\BRS\PageBundle\Entity\Page', $page_id);
				
			$page_file->setPage($page);
			
			
			
			//Utility::die_pre($gallery_file);
			
			$em->persist($page_file);
			
			$em->flush();
		}
	
		return $this->jsonResponse($values);
	}
	
	public function getVars($render = true){
		
		$upload_url = $this->getActionUrl('upload');
		
		$add_vars = array(
			'upload_url' => $upload_url,
		);
		
		$vars = array_merge(parent::getVars($render), $add_vars);
		
		//Utility::die_pre($vars);
		
		return $vars;
	}
}
	
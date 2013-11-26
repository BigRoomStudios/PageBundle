<?php

namespace BRS\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('title', 'text', array('required' => false))
				->add('slug', 'text', array('required' => false))
				->add('description', 'textarea', array('required' => false))
				->add('content', 'collection', array(
					'type' => new ContentType(),
					'allow_add' => true,
					'allow_delete' => true,
					'by_reference' => false,
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'BRS\PageBundle\Entity\Page',
		));
	}
	
	public function getName() {
		return 'page';
	}
	
}
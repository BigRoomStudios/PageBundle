<?php

namespace BRS\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('header', 'text', array('required' => false))
				->add('subHeader', 'text', array('required' => false))
				->add('body', 'textarea', array('required' => false));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'BRS\PageBundle\Entity\Content',
		));
	}
	
	public function getName() {
		return 'content';
	}
	
}
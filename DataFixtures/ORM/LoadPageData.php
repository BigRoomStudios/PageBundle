<?php

namespace BRS\PageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use BRS\PageBundle\Entity\Page;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
    	
		//create a home page
		$home = new Page();
		$home->setTitle('Home');
		$home->setTemplate('BRSPageBundle:Page:home.html.twig');
		
		//save the root page
        $manager->persist($home);
        $manager->flush();
		
		//add a reference for content to use
		$this->addReference('page', $home);
		
    }
	
	/**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1; // the order in which fixtures will be loaded
    }
	
}
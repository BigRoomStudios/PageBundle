<?php

namespace BRS\PageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use BRS\PageBundle\Entity\Content;

class LoadContentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
    	
		//create a test content
		$content = new Content();
		$content->setHeader('Test Header');
		$content->setBody('Test body');
		$content->setPage($this->getReference('page'));
		
		//save the root page
        $manager->persist($content);
        $manager->flush();
		
    }
	
	/**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2; // the order in which fixtures will be loaded
    }
	
}
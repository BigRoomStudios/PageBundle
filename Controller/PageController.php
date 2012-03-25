<?php

namespace BRS\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * PageController handles front-end dynamic page rendering
 * @Route("")
 */
class PageController extends Controller
{
	/**
	 * Passes widget routes on to widget controllers
	 *
	 * @Route("/{route}", requirements={"route" = ".*"})
	 * @Template("BRSPageBundle:Default:index.html.twig")
	 */
	public function pageAction($route)
	{
		return array('route' => $route);
	}
}

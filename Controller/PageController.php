<?php

namespace BRS\PageBundle\Controller;

use BRS\CoreBundle\Core\WidgetController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * PageController handles front-end dynamic page rendering
 * @Route("")
 */
class PageController extends WidgetController
{
	/**
	 * display page content for a given dynamic route
	 *
	 * @Route("/{route}", requirements={"route" = ".*"})
	 * @Template("BRSPageBundle:Page:default.html.twig")
	 */
	public function pageAction($route)
	{
		$page = $this->getRepository('BRSPageBundle:Page')->findOneByRoute($route);
					
		$vars = array(
			'route' => $route,
			'page' => $page,
		);
			
		return $vars;
	}
}

<?php

namespace MinimalOriginal\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
  /**
   * @Route("/", name="minimal_front_home")
   */
    public function indexAction()
    {
        return $this->render('MinimalCoreBundle:Default:index.html.twig');
    }
}

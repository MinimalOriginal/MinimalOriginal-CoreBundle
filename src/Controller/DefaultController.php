<?php

namespace MinimalOriginal\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MinimalOriginal\CoreBundle\Routing\Annotation\Route;


class DefaultController extends Controller
{
  /**
   * @Route("/", name="minimal_front_home", title="Page d'accueil")
   */
    public function indexAction()
    {
        return $this->render('MinimalCoreBundle:Default:index.html.twig');
    }
}

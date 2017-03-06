<?php

namespace XHG\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('XHGCoreBundle:Default:index.html.twig');
    }
}

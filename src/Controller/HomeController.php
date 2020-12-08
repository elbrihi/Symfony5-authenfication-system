<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

     /**
     * @Route("/about", name="app_about")
     */
    public function aboutAction(): Response
    {
        return $this->render('home.html.twig');
    }

     /**
     * @Route("/help", name="help_home")
     */
    public function helpAction(): Response
    {
        return $this->render('home.html.twig');
    }
}

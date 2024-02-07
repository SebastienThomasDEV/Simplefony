<?php

namespace Mvc\Framework\Controller;

use Mvc\Framework\Core\AbstractController;
use Mvc\Framework\Core\Attributes\Route;

class IndexController extends AbstractController
{
    #[Route('/', 'index')]
    public function index()
    {
        $this->render('index/index', ['title' => 'Poo']);
    }


    #[Route('/about', 'about')]
    public function about()
    {
        $this->render('index/about', ['title' => 'About']);
    }




}

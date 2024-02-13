<?php

namespace Mvc\Framework\Controller;

use Mvc\Framework\Core\AbstractController;
use Mvc\Framework\Core\Attributes\Route;

class IndexController extends AbstractController
{
    #[Route('/', 'index')]
    public function showIndex()
    {
        $this->render('index/index', ['title' => 'Poo', 'condition' => true]);
    }


    #[Route('/about', 'about')]
    public function showAbout()
    {

        $this->render('index/about', ['title' => 'About']);
    }




}

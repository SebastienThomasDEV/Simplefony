<?php

namespace Mvc\Framework\App\Controller;

use Mvc\Framework\App\Entity\Product;
use Mvc\Framework\App\Repository\ProductRepository;
use Mvc\Framework\Kernel\AbstractController;
use Mvc\Framework\Kernel\Attributes\Endpoint;
use Mvc\Framework\Kernel\Utils\Request;

class IndexController extends AbstractController
{
    #[Endpoint('/', name: 'index', requestMethod: 'GET')]
    public final function index(Request $request, ProductRepository $productRepository): void
    {
        $product = new Product();
        $product->setName('Velo1');
        $product->setPrice(12.99);
        $product->setDescription('Mon super velo');
        $productRepository->save($product);
        $this->send(["message" => $productRepository->findAll()]);
    }

}

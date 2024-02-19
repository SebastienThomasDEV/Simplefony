<?php

namespace Mvc\Framework\App\Controller;

use Mvc\Framework\App\Entity\Product;
use Mvc\Framework\App\Repository\ProductRepository;
use Mvc\Framework\Kernel\AbstractController;
use Mvc\Framework\Kernel\Attributes\Endpoint;
use Mvc\Framework\Kernel\Utils\Request;

class IndexController extends AbstractController
{
    #[Endpoint('/', 'index')]
    public final function index(Request $request, ProductRepository $productRepository): void
    {
        $product = new Product();
        $product->setName($request->post('name'));
        $product->setDescription($request->post('description'));
        $product->setPrice($request->post('price'));
        $productRepository->save($product);
        $this->send($productRepository->findAll());
    }


}

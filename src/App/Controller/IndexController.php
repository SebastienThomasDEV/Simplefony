<?php

namespace Mvc\Framework\App\Controller;

use Mvc\Framework\App\Entity\Product;
use Mvc\Framework\App\Repository\ProductRepository;
use Mvc\Framework\Kernel\AbstractController;
use Mvc\Framework\Kernel\Attributes\Endpoint;
use Mvc\Framework\Kernel\JwtManager;
use Mvc\Framework\Kernel\Utils\Request;

class IndexController extends AbstractController
{
    #[Endpoint('/products/{id}', name: 'index', requestMethod: 'GET')]
    public final function index(Request $request, ProductRepository $productRepository): void
    {
        dd(JwtManager::generateToken(['id' => 1, 'username' => 'admin', 'roles' => ['ROLE_ADMIN']]));
//        $product = new Product();
//        $product->setName($request->retrievePostValue('name'));
//        $product->setDescription($request->retrievePostValue('description'));
//        $product->setPrice($request->retrievePostValue('price'));
//        $productRepository->save($product);
//        $this->send($productRepository->findAll());
    }


}

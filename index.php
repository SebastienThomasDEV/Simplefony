<?php
include_once('vendor/autoload.php');
use Mvc\Framework\Kernel\Kernel;



try {
    // créer une instance du Kernel
    $kernel = new Kernel();
} catch (Exception $e) {
    // sinon nous une erreur
    echo $e->getMessage();
}


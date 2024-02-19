<?php
include_once('vendor/autoload.php');
use Mvc\Framework\Kernel\Kernel;



try {
    $kernel = new Kernel();
} catch (Exception $e) {
    echo $e->getMessage();
}


<?php

use Symfony\Component\Dotenv\Dotenv;

putenv('APP_ENV='.$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'test');
require dirname(__DIR__, 2).'/config/bootstrap.php';

if(!isset($_SERVER['APP_ENV'])){
    if(!class_exists(Dotenv::class)){
        throw new \RuntimeException('APP_ENV enviroment variable is not defined. You need to define it');
    }
    (new Dotenv())->load(__DIR__.'/../../.env.test');
}
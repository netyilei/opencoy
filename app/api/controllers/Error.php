<?php

class ErrorController extends Yaf\Controller_Abstract
{
    public function errorAction($exception)
    {
        echo '<pre>';
        var_dump($exception);
        return false;
    }
}
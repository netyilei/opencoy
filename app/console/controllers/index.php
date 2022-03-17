<?php

use cores\ship\ShipBase;
use models\ship\Orders;
use models\user\User;
use models\user\Records;
use models\system\ErrorLog;

class IndexController extends \Yaf\Controller_Abstract
{

    public function indexAction()
    {
        echo "Hello OpenCoy Cli";
    }
}

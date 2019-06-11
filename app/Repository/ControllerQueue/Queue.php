<?php
/**
 * Created by PhpStorm.
 * User: João Paulo Dos S.R.
 * E-Mail: jpaulolxm@gmail.com
 * Date: 08/10/2018
 * Time: 14:45
 */

namespace App\Repository\ControllerQueue;



class Queue extends StrategyControllerQueue
{

    public function execute()
    {
        $this->start();

        ($this->job)();
    }
}
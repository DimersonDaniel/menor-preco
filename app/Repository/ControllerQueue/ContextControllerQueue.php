<?php
/**
 * Created by PhpStorm.
 * User: JPaulo
 * Date: 18/06/2018
 * Time: 16:21
 */

namespace App\Repository\ControllerQueue;


class ContextControllerQueue
{
    /** @var StrategyControllerQueue $strategy Stretegy de importação de produtos. */
    private $strategy;

    /**
     * StrategyControllerQueue constructor.
     * @param StrategyControllerQueue $strategy
     */
    public function __construct(StrategyControllerQueue $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param StrategyControllerQueue $strategy
     */
    public function setStrategy(StrategyControllerQueue $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Método responsável por executar a estratégia de queue.
     */
    public function execute()
    {
        $this->strategy->execute();
    }



}
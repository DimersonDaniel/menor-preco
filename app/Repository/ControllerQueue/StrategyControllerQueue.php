<?php
/**
 * Created by PhpStorm.
 * User: JPaulo
 * Date: 18/06/2018
 * Time: 15:54
 */

namespace App\Repository\ControllerQueue;

use App\Models\WorkingProcess;


abstract class StrategyControllerQueue
{
    public  $id;
    private $idSituacao;
    private $idTipo;
    private $fullPath;
    private $descricao;
    public  $job;
    private $idUser;

    abstract public function execute();

    public function start()
    {
        $queue = new WorkingProcess();

        $queue->id_job_situacao              = $this->idSituacao;
        $queue->id_job_tipo                  = $this->idTipo;
        $queue->full_path                    = ($this->fullPath === null)? '': $this->fullPath;
        $queue->descricao                    = $this->descricao;
        $queue->created_at                   = new \DateTime();
        $queue->id_user                      = $this->getIdUser();
        $queue->save();

        $this->id = $queue->id;
    }

    public function finish(int $idDown, $fullPath, int $idSituacao)
    {
        $queue  = WorkingProcess::find($idDown);

        $queue->id_job_situacao         = $idSituacao;
        $queue->full_path               = ($fullPath === null)? '': $fullPath;
        $queue->save();

    }

    public function error($id)
    {
        $queue  = WorkingProcess::find($id);

        $queue->id_job_situacao = 3;
        $queue->save();

    }

    /**
     * @param mixed $idSituacao
     * @return StrategyControllerQueue
     */
    public function setIdSituacao($idSituacao)
    {
        $this->idSituacao = $idSituacao;
        return $this;
    }

    /**
     * @param mixed $idTipo
     * @return StrategyControllerQueue
     */
    public function setIdTipo($idTipo)
    {
        $this->idTipo = $idTipo;
        return $this;
    }

    /**
     * @param mixed $fullPath
     * @return StrategyControllerQueue
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    /**
     * @param mixed $descricao
     * @return StrategyControllerQueue
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @param mixed $job
     * @return StrategyControllerQueue
     */
    public function setJob($job)
    {
        $this->job = $job;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     * @return StrategyControllerQueue
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
        return $this;
    }




}
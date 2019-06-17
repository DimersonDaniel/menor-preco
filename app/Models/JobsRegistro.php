<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Eloquent
 * @property int id
 * @property int id_situacao
 * @property int id_queue
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class JobsRegistro extends Model
{
    protected $table = 'jobs__registro';
    public $timestamps = true;

    public function situacao(){
        return $this->hasOne(JobsSituacao::class,'id','id_situacao');
    }

    public function queue(){
        return $this->hasOne(JobsQueue::class,'id','id_queue');
    }

}

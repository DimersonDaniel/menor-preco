<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Eloquent
 * @property int id
 * @property string file_name
 * @property string descricao
 * @property string path
 * @property string data
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class JobsQueue extends Model
{
    protected $table = 'jobs__queues';
    public $timestamps = true;//
}

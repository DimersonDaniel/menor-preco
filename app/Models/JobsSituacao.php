<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Eloquent
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class JobsSituacao extends Model
{
    protected $table = 'jobs__situacao';
    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Eloquent
 * @property int id
 * @property string name
 * @property int codigo_barra
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class StoreProducts extends Model
{
    use SoftDeletes;
    protected $table = 'store__produtos';

    public $timestamps = true;
}

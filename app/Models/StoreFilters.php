<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Eloquent
 * @property int id
 * @property string name
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class StoreFilters extends Model
{
    use SoftDeletes;
    protected $table = 'store__filters';

    public $timestamps = true;
}

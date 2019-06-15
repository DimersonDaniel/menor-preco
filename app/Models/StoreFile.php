<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Eloquent
 * @property int id
 * @property string file_name
 * @property string file_path
 * @property string descricao
 * @property string data
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class StoreFile extends Model
{
    use SoftDeletes;
    protected $table = 'store__file_download';

    public $timestamps = true;
}

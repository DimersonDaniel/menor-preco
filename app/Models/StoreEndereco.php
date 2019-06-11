<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @mixin Eloquent
 * @property int id
 * @property int id_user
 * @property string local
 * @property string apelido
 * @property string endereco
 * @property int active
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class StoreEndereco extends Model
{
    use SoftDeletes;
    protected $table = 'store__endereco';

    protected $casts = [
        'updated_at'  => 'datetime:d/m/Y',
        'created_at'  => 'datetime:d/m/Y',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->hasOne(User::class,'id','id_user');
    }

}

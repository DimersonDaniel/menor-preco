<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @mixin Eloquent
 * @property int id
 * @property int id_user
 * @property int id_produto
 * @property int id_endereco
 * @property float valor
 * @property \DateTime dateEntrada
 * @property string horaEntrada
 * @property \DateTime created_at
 * @property \DateTime updated_at
 */
class StoreConsultas extends Model
{
    use SoftDeletes;
    protected $table = 'store__consultas';

    public $timestamps = true;

    public function produto()
    {
        return $this->hasOne(StoreProducts::class, 'id','id_produto');
    }

}

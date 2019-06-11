<?php

use App\Models\StoreConsultas;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $table;
    public function __construct()
    {
        $this->table   = (new StoreConsultas())->getTable();
    }

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user');
            $table->integer('id_produto')->index('id_produto');
            $table->integer('id_endereco')->index('id_endereco');
            $table->decimal('valor',10,2);
            $table->date('dateEntrada');
            $table->string('horaEntrada');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}

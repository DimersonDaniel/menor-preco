<?php

use App\Models\StoreEndereco;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $table;
    public function __construct()
    {
        $this->table   = (new StoreEndereco())->getTable();
    }

    public function up()
    {
        Schema::create( $this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user')->index('id_user');
            $table->string('local')->index('local')->nullable();
            $table->string('apelido')->index('apelido')->nullable();
            $table->string('endereco')->index('endereco')->nullable();
            $table->integer('active')->nullable();
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
        Schema::dropIfExists( $this->table);
    }
}

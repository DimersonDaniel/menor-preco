<?php

use App\Models\JobsSituacao;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsSituacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private $table;
    public function __construct()
    {
        $this->table   = (new JobsSituacao())->getTable();
    }


    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('class_button');
            $table->timestamps();
        });

        JobsSituacao::insert([
            ['id' => 1 ,'name' => 'PROCESSANDO', 'class_button' => 'warning'],
            ['id' => 2 ,'name' => 'CONCLUIDO', 'class_button' => 'success'],
            ['id' => 3 ,'name' => 'ERRO', 'class_button' => 'error'],
        ]);
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

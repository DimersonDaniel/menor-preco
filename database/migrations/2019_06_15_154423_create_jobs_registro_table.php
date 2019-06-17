<?php

use App\Models\JobsRegistro;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private $table;
    public function __construct()
    {
        $this->table   = (new JobsRegistro())->getTable();
    }


    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_situacao');
            $table->integer('id_queue');
            $table->string('name');
            $table->timestamps();
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

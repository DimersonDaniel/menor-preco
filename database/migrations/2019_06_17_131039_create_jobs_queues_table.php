<?php

use App\Models\JobsQueue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private $table;
    public function __construct()
    {
        $this->table   = (new JobsQueue())->getTable();
    }


    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name');
            $table->string('descricao');
            $table->string('path');
            $table->timestamp('data');
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

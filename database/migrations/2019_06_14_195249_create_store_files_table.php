<?php

use App\Models\StoreFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private $table;
    public function __construct()
    {
        $this->table   = (new StoreFile())->getTable();
    }

    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('descricao')->nullable();
            $table->timestamp('data')->nullable();
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
        Schema::dropIfExists($this->table );
    }
}

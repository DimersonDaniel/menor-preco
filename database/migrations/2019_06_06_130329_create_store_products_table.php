<?php

use App\Models\StoreProducts;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $table;
    public function __construct()
    {
        $this->table   = (new StoreProducts())->getTable();
    }

    public function up()
    {
        Schema::create( $this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index('name')->nullable();
            $table->bigInteger('codigo_barra')->index('codigo_barra')->nullable();
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

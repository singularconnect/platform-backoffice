<?php

use brunojk\LaravelRethinkdb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesHistoricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges_historics', function (Blueprint $table) {
            $table->index('pair');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exchanges_historics');
    }
}
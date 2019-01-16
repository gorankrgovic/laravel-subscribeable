<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribeCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_counters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subscribeable_id')->nullable();
            $table->uuid('subscribeable_type')->nullable();
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps();

            $table->unique([
                'subscribeable_type',
                'subscribeable_id',
            ], 'subscribe_counter_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribe_counters');
    }
}

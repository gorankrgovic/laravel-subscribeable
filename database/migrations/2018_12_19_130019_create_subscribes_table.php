<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subscribeable_id')->nullable();
            $table->uuid('subscribeable_type')->nullable();
            $table->uuid('user_id')->nullable()->index();
            $table->timestamps();

            $table->unique([
                'subscribeable_type',
                'subscribeable_id',
                'user_id',
            ], 'subscribe_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribes');
    }
}

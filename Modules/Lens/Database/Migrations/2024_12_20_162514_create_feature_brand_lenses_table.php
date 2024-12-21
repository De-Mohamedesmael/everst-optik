<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureBrandLensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_brand_lenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brand_lenses')->onDelete('cascade');
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_lens');
    }
}

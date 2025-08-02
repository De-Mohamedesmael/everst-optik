<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->decimal('amount_product', 15, 4)->default(0)->comment('price of lenses')->after('factory_id');
            $table->decimal('total_extra', 15, 4)->default(0)->comment('total of extras')->after('amount_product');
            $table->decimal('amount_total', 15, 4)->default(0)->comment('total of lenses and extras')->after('total_extra');
            $table->string('qr_code')->comment('qr Code')->after('amount_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {

        });
    }
}

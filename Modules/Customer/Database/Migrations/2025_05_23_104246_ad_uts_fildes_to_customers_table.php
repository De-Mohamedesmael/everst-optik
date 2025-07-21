<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdUtsFildesToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('BNO')->after('id_number')->nullable()->comment('رقم البند أو رقم الوحدة ');
            $table->enum('id_type',['TC_KIMLIK_NUMARASI_VAR','YABANCI_KIMLIK_NUMARASI_VAR','PASAPORT_NUMARASI_VAR','YUPASS_NUMARASI_VAR','KIMLIGI_BELIRSIZ','DIGER'])
                ->after('id_number')->default('TC_KIMLIK_NUMARASI_VAR');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

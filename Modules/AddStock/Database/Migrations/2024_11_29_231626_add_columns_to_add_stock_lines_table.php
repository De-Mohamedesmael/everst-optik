<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_stock_lines', function (Blueprint $table) {
            $table->decimal('sell_price', 15, 4)->after('purchase_price');
            $table->integer('bounce_qty')->nullable()->after('manufacturing_date');
            $table->double('profit_bounce')->nullable()->after('bounce_qty');
            $table->decimal('cost_ratio_per_one')->nullable()->after('profit_bounce');
            $table->decimal('quantity_damaged', 15, 4)->default(0)->after('cost_ratio_per_one');
            $table->unsignedBigInteger('updated_by')->nullable()->after('quantity_damaged');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_stock_lines', function (Blueprint $table) {
            $table->dropColumn(['sell_price','cost_ratio_per_one','bounce_qty','profit_bounce','quantity_damaged','updated_by']);
        });
    }
};

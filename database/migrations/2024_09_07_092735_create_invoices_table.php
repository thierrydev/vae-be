<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $statuses = Config::get('constants.INVOICE_STATUSES');
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->integer('amount');
            $table->enum('status', $statuses);
            $table->dateTime('billed_date');
            $table->dateTime('paid_date')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}

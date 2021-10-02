<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $userTable = (new User())->getTable();

            $table->id();
            $table->foreignIdFor(new User(), 'customer_id')->constrained($userTable);
            $table->foreignIdFor(new User(), 'biller_id')
                ->nullable()
                ->constrained($userTable);
            $table->unsignedDecimal('paid');
            $table->enum('status', Order::STATUSES);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

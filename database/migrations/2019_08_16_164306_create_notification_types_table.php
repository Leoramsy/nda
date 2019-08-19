<?php

use App\Models\NotificationType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTypesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('notification_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('slug');
            $table->timestamps();
        });

        // Create 2 notification_types
        NotificationType::create(['slug' => NotificationType::EMAIL, 'description' => strtoupper(NotificationType::EMAIL)]);
        NotificationType::create(['slug' => NotificationType::SMS, 'description' => strtoupper(NotificationType::SMS)]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('notification_types');
    }

}

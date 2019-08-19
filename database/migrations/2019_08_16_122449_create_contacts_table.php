<?php

use App\Models\Contact;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('province_id');
            $table->string('name');
            $table->string('surname');
            $table->string('mobile_number');
            $table->string('email')->nullable()->unique();
            $table->boolean('opt_in')->default(FALSE);
            $table->ipAddress('source_ip')->nullable()->default(NULL);
            $table->string('user_agent')->nullable()->default(NULL);
            $table->timestamp('opt_in_date')->nullable()->default(NULL);
            $table->string('auth_code')->nullable()->default(NULL)->comment('Auth code to be sent when the user is asked to opt in and verify cuntact identity');
            $table->timestamps();
            
            $table->index('province_id');            
            
        });

        // Create dummy contact
        Contact::create(['province_id' => 1,
            'name' => 'Leo',
            'surname' => 'Rams',
            'mobile_number' => '+27659690704',
            'email' => 'leoramsy@gmail.com',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('contacts');
    }

}

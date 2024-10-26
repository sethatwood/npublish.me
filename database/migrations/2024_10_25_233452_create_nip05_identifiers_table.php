<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNip05IdentifiersTable extends Migration
{
    public function up()
    {
        Schema::create('nip05_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('public_key');
            $table->string('email')->nullable();
            $table->string('passkey')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nip05_identifiers');
    }
}

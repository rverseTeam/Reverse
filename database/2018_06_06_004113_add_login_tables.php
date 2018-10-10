<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Miiverse\DB;

class AddLoginTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::getSchemaBuilder();

        $schema->create('login_attempts', function (Blueprint $table) {
            $table->increments('attempt_id');

            $table->tinyInteger('attempt_success')
                ->unsigned();

            $table->integer('attempt_timestamp')
                ->unsigned();

            $table->binary('attempt_ip');

            $table->integer('user_id')
                ->unsigned();
        });

        $schema->table('users', function (Blueprint $table) {
            $table->string('password')
                ->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema = DB::getSchemaBuilder();

        $schema->table('users', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
}

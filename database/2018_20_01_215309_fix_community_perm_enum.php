<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Miiverse\DB;

class FixCocmmunityPermEnum extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		$schema = DB::getSchemaBuilder();

		$schema->table('communities', function (Blueprint $table) {
			$table->dropColumn('permissions');

			$table->smallInteger('permissions')->unsigned()->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		$schema = DB::getSchemaBuilder();

		$schema->table('communities', function (Blueprint $table) {
			$table->dropColumn('permissions');

			$table->enum('permissions', ['post', 'draw', 'like']);
		});
	}
}

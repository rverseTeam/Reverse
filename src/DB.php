<?php
/**
 * Holds the alias class for the Illuminate database thing.
 * @package Miiverse
 */

namespace Miiverse;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\ConnectionResolver;

/**
 * The Illuminate (Laravel) database wrapper.
 * @package Miiverse
 * @author Repflez
 */
class DB extends Manager
{
	/**
	 * Start the database module.
	 * @param array $details
	 */
	public static function connect(array $details) : void {
		$capsule = new static;
		$capsule->addConnection($details);
		$capsule->setAsGlobal();
	}

	/**
	 * Gets the migration repository (surprise surprise).
	 * @return DatabaseMigrationRepository
	 */
	public static function getMigrationRepository() : DatabaseMigrationRepository {
		$resolver = new ConnectionResolver(['database' => self::connection()]);
		$repository = new DatabaseMigrationRepository($resolver, 'migrations');
		$repository->setSource('database');
		return $repository;
	}

	/**
	 * Get the migration schema builder.
	 * @return Builder
	 */
	public static function getSchemaBuilder() : Builder {
		return self::connection()->getSchemaBuilder();
	}
}

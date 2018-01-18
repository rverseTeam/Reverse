<?php
/**
 * Holds the migration repository installer command controller.
 * @package Miiverse
 */

namespace Miiverse\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Miiverse\DB;

/**
 * Installs the database migration repository.
 * @package Miiverse
 * @author Repflez
 */
class DatabaseInstallCommand extends Command
{
	/**
	 * Set up the command metadata.
	 */
	protected function configure() : void {
		$this->setName('db:install')->setDescription('Create the migration repository.')->setHelp('This command creates the database migration repository for TestVerse.')
		;
	}
	/**
	 * Does the repository installing.
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$repository = DB::getMigrationRepository();
		$migrator = new Migrator($repository, $repository->getConnectionResolver(), new Filesystem);

		if ($migrator->repositoryExists()) {
			$output->writeln("The migration repository already exists!");
			return 0;
		}

		$repository->createRepository();
		$output->writeln("Created the migration repository!");
	}
}

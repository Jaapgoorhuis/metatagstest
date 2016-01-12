<?php
/**
 * Laravel 4 - Persistant Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-settings
 */

namespace jaappgoorhuis\LaravelMetatags;

use Illuminate\Support\Manager;
use Illuminate\Foundation\Application;

class MetatagsManager extends Manager
{
	public function getDefaultDriver()
	{
		return $this->getConfig('jaapgoorhuis/l4-metatags::store');
	}

	public function createJsonDriver()
	{
		$path = $this->getConfig('jaapgoorhuis/l4-metatags::path');

		return new JsonMetatagStore($this->app['files'], $path);
	}

	public function createDatabaseDriver()
	{
		$connectionName = $this->getConfig('jaapgoorhuis/l4-metatags::connection');
		$connection = $this->app['db']->connection($connectionName);
		$table = $this->getConfig('jaapgoorhuis/l4-metatags::table');

		return new DatabaseMetatagStore($connection, $table);
	}

	public function createMemoryDriver()
	{
		return new MemoryMetatagStore();
	}

	public function createArrayDriver()
	{
		return $this->createMemoryDriver();
	}

	protected function getConfig($key)
	{
		if (version_compare(Application::VERSION, '5.0', '>=')) {
			$key = str_replace('jaappgoorhuis/l4-metatags::', 'metatags.', $key);
		}

		return $this->app['config']->get($key);
	}
}

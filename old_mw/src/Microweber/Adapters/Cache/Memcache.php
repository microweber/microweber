<?php
namespace Microweber\Adapters\Cache;
$mw_cache_get_content_memory = array();
$mw_skip_memory = array();

$memcache_enabled = extension_loaded('memcache');

class Memcache   {
	public $mw_cache_mem = array();
	public $mw_cache_mem_hits = array();
	public $connected_server = false;
	public $cache_fallback = false;

	function __construct() {

		$memcache_enabled = extension_loaded('memcache');

		if ($memcache_enabled == true) {

			$memcache_servers = $this->app->option->get_static('memcache_servers', 'server');
			if ($memcache_servers != false) {
				$memcache_servers = trim($memcache_servers);
				$memcache_servers = explode(',', $memcache_servers);
			}

			$conn = false;

			if (is_array($memcache_servers)) {
				foreach ($memcache_servers as $item) {
					if ($conn == false) {
						$h = explode(':', $item);
						if (!isset($h[1])) {
							$h[1] = 11211;
						} else {
							$h[1] = trim($h[1]);
						}
						$h[0] = trim($h[0]);
						$host = $h[0];
						$port = $h[1];
						$memcache = new \memcache();
						$memcache -> addServer($h[0], $h[1]);
						$stats = @$memcache -> getExtendedStats();
						$available = (bool)$stats["$host:$port"];
						if ($available && @$memcache -> connect($host, $port)) {
							// memcache is there
							$conn = 1;
							$this->connected_server = $memcache;

						} else {
							$conn = false;
						}
					}
				}
			}

		} else {
			//falling back to the files cache
			$this->cache_fallback = new files;

		}
		if ($this->connected_server == false) {
			$this->cache_fallback = new files;
		}
		//d($this->servers);

	}

	public function save($data_to_cache, $cache_id, $cache_group = 'global') {

		if ($this->connected_server == false) {
			return $this->cache_fallback -> save($data_to_cache, $cache_id, $cache_group);
		}
		$cache_id = $cache_id . $cache_group;
		$data_to_cache = serialize($data_to_cache);
		$cache = MW_CACHE_CONTENT_PREPEND . $data_to_cache;
		return $this->connected_server -> set($cache_id, $cache, false, APC_EXPIRES);

	}

	public function delete($cache_group = 'global') {
		if ($this->connected_server == false) {
			return $this->cache_fallback -> delete($cache_group);
		}

		return $this->connected_server -> flush();

	}

	public function debug() {

		if ($this->connected_server == false) {
			return $this->cache_fallback -> debug();
		}

		$status = $this->connected_server -> getStats();

		echo "<table border='1'>";

		echo "<tr><td>Memcache Server version:</td><td> " . $status["version"] . "</td></tr>";
		echo "<tr><td>Process id of this server process </td><td>" . $status["pid"] . "</td></tr>";
		echo "<tr><td>Number of seconds this server has been running </td><td>" . $status["uptime"] . "</td></tr>";
		echo "<tr><td>Accumulated user time for this process </td><td>" . $status["rusage_user"] . " seconds</td></tr>";
		echo "<tr><td>Accumulated system time for this process </td><td>" . $status["rusage_system"] . " seconds</td></tr>";
		echo "<tr><td>Total number of items stored by this server ever since it started </td><td>" . $status["total_items"] . "</td></tr>";
		echo "<tr><td>Number of open connections </td><td>" . $status["curr_connections"] . "</td></tr>";
		echo "<tr><td>Total number of connections opened since the server started running </td><td>" . $status["total_connections"] . "</td></tr>";
		echo "<tr><td>Number of connection structures allocated by the server </td><td>" . $status["connection_structures"] . "</td></tr>";
		echo "<tr><td>Cumulative number of retrieval requests </td><td>" . $status["cmd_get"] . "</td></tr>";
		echo "<tr><td> Cumulative number of storage requests </td><td>" . $status["cmd_set"] . "</td></tr>";

		$percCacheHit = ((real)$status["get_hits"] / (real)$status["cmd_get"] * 100);
		$percCacheHit = round($percCacheHit, 3);
		$percCacheMiss = 100 - $percCacheHit;

		echo "<tr><td>Number of keys that have been requested and found present </td><td>" . $status["get_hits"] . " ($percCacheHit%)</td></tr>";
		echo "<tr><td>Number of items that have been requested and not found </td><td>" . $status["get_misses"] . "($percCacheMiss%)</td></tr>";

		$MBRead = (real)$status["bytes_read"] / (1024 * 1024);

		echo "<tr><td>Total number of bytes read by this server from network </td><td>" . $MBRead . " Mega Bytes</td></tr>";
		$MBWrite = (real)$status["bytes_written"] / (1024 * 1024);
		echo "<tr><td>Total number of bytes sent by this server to network </td><td>" . $MBWrite . " Mega Bytes</td></tr>";
		$MBSize = (real)$status["limit_maxbytes"] / (1024 * 1024);
		echo "<tr><td>Number of bytes this server is allowed to use for storage.</td><td>" . $MBSize . " Mega Bytes</td></tr>";
		echo "<tr><td>Number of valid items removed from cache to free memory for new items.</td><td>" . $status["evictions"] . "</td></tr>";

		echo "</table>";

		//return apc_cache_info('user');

	}

	public function get($cache_id, $cache_group = 'global', $time = false) {

		if ($this->connected_server == false) {
			return $this->cache_fallback -> get($cache_id, $cache_group, $time);
		}

		$cache_id_saved = $cache_id . $cache_group;
		$cache = $this->connected_server -> get($cache_id_saved);

		if ($cache) {
			if (isset($cache) and strval($cache) != '') {

				$search = MW_CACHE_CONTENT_PREPEND;

				$replace = '';

				$count = 1;

				$cache = str_replace($search, $replace, $cache, $count);
				$cache = unserialize($cache);
			}

			return $cache;
		}

		return false;
	}

	public function purge() {
		if ($this->connected_server == false) {
			return $this->cache_fallback -> purge();
		}
		return $this->connected_server -> flush();
	}

	//$mw_cache_mem = array();

}

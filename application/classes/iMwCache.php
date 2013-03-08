<?

interface  iMwCache {

	public function save($data_to_cache, $cache_id, $cache_group = 'global');

	public function get($cache_id, $cache_group = 'global', $time = false);

	public function delete($cache_group = 'global');
	public function purge();
	public function debug();

}

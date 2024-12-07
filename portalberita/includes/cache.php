<?php
class Cache {
    private $cache_path = 'cache/';
    private $cache_time = 3600; // 1 jam dalam detik

    public function __construct() {
        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0777, true);
        }
    }

    public function getCache($key) {
        $filename = $this->cache_path . md5($key) . '.cache';
        
        if (file_exists($filename) && (time() - filemtime($filename) < $this->cache_time)) {
            return unserialize(file_get_contents($filename));
        }
        
        return false;
    }

    public function setCache($key, $data) {
        $filename = $this->cache_path . md5($key) . '.cache';
        return file_put_contents($filename, serialize($data));
    }

    public function deleteCache($key) {
        $filename = $this->cache_path . md5($key) . '.cache';
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
?> 
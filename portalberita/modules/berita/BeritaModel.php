<?php
class BeritaModel {
    private $db;
    private $cache;
    
    public function __construct() {
        $this->db = getConnection();
        $this->cache = new Cache();
    }
    
    public function getBerita($id) {
        $cache_key = 'berita_detail_' . $id;
        
        // Cek cache
        $berita = $this->cache->getCache($cache_key);
        if ($berita !== false) {
            return $berita;
        }
        
        // Query database
        $stmt = mysqli_prepare($this->db, "SELECT * FROM berita WHERE ID = ? AND terbit = '1'");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $berita = mysqli_fetch_assoc($result);
        
        // Set cache
        $this->cache->setCache($cache_key, $berita);
        
        return $berita;
    }
    
    // Metode lainnya...
} 
<?php
class BeritaController {
    private $model;
    
    public function __construct() {
        $this->model = new BeritaModel();
    }
    
    public function detail($id) {
        $berita = $this->model->getBerita($id);
        if ($berita) {
            $this->model->updateViews($id);
            include 'modules/berita/views/detail.php';
        } else {
            // Handle error
            header("Location: index.php");
        }
    }
    
    // Method lainnya...
} 
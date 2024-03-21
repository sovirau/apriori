<?php 
;
  if(isset($_POST['submit'])){
    $id_file = $_POST['sc'];
    $min_support = $_POST['min_support'];
    $min_confidence = $_POST['min_confidence'];

    $insert_process = mysqli_query($koneksi, "INSERT INTO proses (id_file, min_support, min_confidence) VALUES ($id_file, $min_support, $min_confidence)");
    $sql_transformasi = mysqli_query($koneksi, "UPDATE tb_databarang SET no_transaksi = REPLACE(no_transaksi, '/KSR/UTM/101901-10-19', '') WHERE status = 'aktif'");

    
  }

?>
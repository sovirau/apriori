<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengolahan Data</h1></div>

    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
    </div>
    <div class="card-body">
    <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama File</th>
                      <th>Aksi</th>
                    </tr>
                    
                  </thead>
                    <?php
                    $no = 1;
                    $data = mysqli_query($koneksi, "select * from tb_file");
                    while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                      <td><?php echo $no++; ?></td>
                      <td><a><?php echo $d['nama_file']; ?></a></td>
                      <td><a href="?pages=viewitem&id=<?php echo $d['id_file']; ?>">Lihat Data</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?pages=olahdata&action=delete&id=<?php echo $d['id_file']; ?>" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')">HAPUS</a></td>
                    </tr>
                    <?php } ?>
                </table>
    </div>
    </div>
    </div>
    <?php
if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $id = $_GET['id'];
    $s = mysqli_query($koneksi, "SELECT * from tb_file where id_file = $id");
    $data2 = mysqli_fetch_array($s);
    $delete = mysqli_query($koneksi, "DELETE from tb_file where id_file = '$id'");

    if($delete==true){
        unlink("../assets/excel/".$data2['nama_file']);
        echo "<script language = javascript> document.location='?pages=dtbrg&alert=c'; </script>";
    } else { echo "<script language = javascript> alert('Data Gagal Dihapus'); document.location='?pages=olahdata'; </script>"; }
    }
    ?>
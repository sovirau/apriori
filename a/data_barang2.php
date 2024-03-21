<head>
  <!-- <script type = "text/javascript" >
  window.onbeforeunload = function() { return "Are you sure to leave this page?"; }; 
  </script> -->
</head>
<?php
  include('mining.php');
  $sql2 = mysqli_query($koneksi, "SELECT * FROM tb_file ORDER BY id_file DESC LIMIT 1");
  $data2 = mysqli_fetch_array($sql2);
  $sql_count = mysqli_query($koneksi, "SELECT count(DISTINCT no_transaksi) as jumlah_transaksi from tb_databarang where status = 'aktif'");
  $jumlah_transaksi = mysqli_fetch_array($sql_count);
  $jumlah = $jumlah_transaksi['jumlah_transaksi'];

    
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Barang</h1></div>

    <div id = "content">
    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Penjualan Barang <?php echo $data2['id_file'];?>
        </h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6"><p>Jumlah Transaksi: <?php echo $jumlah; ?> transaksi</p></div>
        <div class="col-6">
        <a href="#exampleModalCenter" data-toggle="modal" data-target="#exampleModalCenter" data-toggle="modal" class = "float-right btn btn-success">Proses Data</i></a>
        </div>
    </div>
      <br>
    <div class="table-responsive">
    <table id="dataTable" class="table table-hovered table-striped" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nomor Transaksi</th>
                      <th>Nama Barang</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th>Nomor Transaksi</th>
                      <th>Nama Barang</th>
                    </tr>
                  </tfoot>
                    <?php
                    $no = 1;
                    $data = mysqli_query($koneksi, "SELECT * from tb_databarang WHERE status = 'aktif'");
                    while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                      <td><?php echo $no++; ?></td>
                      <td><?php echo $d['no_transaksi']; ?></td>
                      <td><?php echo $d['nama_barang']; ?></td>
                    </tr>
                    <?php } ?>
    </table>
    </div>
        
    </div>
    </div>
    </div>

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Masukkan min. support & confidence</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form class="form-group" id="transformasi" action="?pages=viewitem" method="post" role="form">
                    <div class="form-group">
                        <label class="col-form-label">Min. Support</label>
                        <input type="hidden" name="sc" value="<?php echo $data2['id_file'];?>">
                        <input type="number" class="form-control" id="min_support" placeholder="Masukkan Nilai Min. Support" name="min_support" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="inputPassword">Min. Confidence</label>
                        <input type="number" class="form-control" id="min_confidence" placeholder="Masukkan Nilai Min. Confidence" name="min_confidence" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <input type="submit" id="submit" name="submit" value="Proses Data" class="btn btn-success">
                    </form>
                </div>
                </div>
            </div>
            </div>
           

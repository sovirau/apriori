<?php
  if(isset($_POST['submit'])){
    $id_file = $_POST['sc'];
    $min_support = $_POST['min_support'];
    $min_confidence = $_POST['min_confidence'];
    $tanggal = date("Y-m-d H:i:s");
    mysqli_query($koneksi, "INSERT INTO proses (id_file, min_suppport, min_confidence) VALUES ('$id_file', '$min_support', '$min_confidence')");
    mysqli_query($koneksi, "UPDATE tb_databarang SET no_transaksi = REPLACE(no_transaksi, '/KSR/UTM/101901-10-19', '') WHERE status = 'aktif'");
    mysqli_query($koneksi, "delete from tb_databarang where status = 'aktif' and no_transaksi = ANY(select no_transaksi from tb_databarang where status = 'aktif' group by no_transaksi having count(no_transaksi) <= 1)");
    
      } else

  if(isset($_GET['id'])){
    $id_file = $_GET['id'];
    $cari_data = mysqli_query($koneksi, "SELECT * FROM tb_file
    inner join proses
    on proses.id_file = tb_file.id_file where tb_file.id_file = ".$id_file);
    $lihatdata = mysqli_fetch_array($cari_data);
    $min_support = $lihatdata['min_suppport'];
    $min_confidence = $lihatdata['min_confidence'];
    $id_proses = $lihatdata['id_proses'];
  }

      $sql_countdata = mysqli_query($koneksi, "SELECT *, count(DISTINCT no_transaksi) as jumlah_transaksi from tb_databarang where status = 'aktif'");
      $countdata = mysqli_fetch_array($sql_countdata);
      $totaldata = $countdata['jumlah_transaksi'];
      $ds_itemset1 = mysqli_query($koneksi, "SELECT a.item as item, a.support as support, a.lolos as lolos, a.id_proses as id_proses, a.jumlah as jumlah FROM itemset1 a INNER JOIN proses b ON a.id_proses = b.id_proses WHERE a.lolos = 1 and a.id_proses = $id_proses ORDER BY a.item");
      $itemset1_lolosq = mysqli_fetch_array($ds_itemset1);
      $dt = mysqli_query($koneksi, "SELECT nama_barang, count(nama_barang) as jumlah_brg from tb_databarang WHERE status = 'aktif' GROUP BY nama_barang");
      $dtq = mysqli_fetch_array($dt);



      
?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Penjualan Barang</h6>
    </div>
    <div class="card-body">
    <div class="row">
    <div class="col-6">
    <input type="hidden" name="sc" value="<?php echo $data2['id_file'];?>">
    <p>Min. Support: <?php echo $min_support; ?></p>
    <p>Min. Confidence: <?php echo $min_confidence; ?></p> 
    <p>Total Transaksi: <?php echo $totaldata; ?> transaksi</p>
    </div>
    <div class="col-6 text-right"> <a class="btn btn-primary" href="../fpdf/pdfdm.php?id=<?php echo $id_proses; ?>" target="_blank"><i class="fas fa-fw fa-print"></i> Cetak</a>
    </div>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#itemset1" role="tab" aria-controls="itemset1"
          aria-selected="true">Itemset 1</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#itemset2" role="tab" aria-controls="itemset2"
          aria-selected="false">Itemset 2</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#itemset3" role="tab" aria-controls="itemset3"
          aria-selected="false">Itemset 3</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#confidence" role="tab" aria-controls="itemset3"
          aria-selected="false">Confidence</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#lift_ratio" role="tab" aria-controls="itemset3"
          aria-selected="false">Lift Ratio</a>
      </li>
    </ul>
<div class="tab-content" id="myTabContent">
<div class="tab-pane fade show active" id="itemset1" role="tabpanel" aria-labelledby="itemset1-tab"><br>
     <?php include("vitem1.php"); ?>
</div>

<div class="tab-pane fade" id="itemset2" role="tabpanel" aria-labelledby="itemset2-tab">
      <!-- ITEMSET2 -->
      <?php include("vitem2.php"); ?>
</div>
<div class="tab-pane fade" id="itemset3" role="tabpanel" aria-labelledby="contact-tab"><?php include("vitem3.php"); ?>
</div>

<div class="tab-pane fade" id="confidence" role="tabpanel" aria-labelledby="itemset2-tab">
      <!-- ITEMSET2 -->
      <?php include("vconf.php"); ?>
</div>
<div class="tab-pane fade" id="lift_ratio" role="tabpanel" aria-labelledby="itemset2-tab">
      <!-- ITEMSET2 -->
      <?php include("vlift.php"); ?>
</div>
</div>

    
        
    </div>
    </div>
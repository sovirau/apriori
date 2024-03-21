<?php
include('../config.php');
if(isset($_GET['id'])){
  $id_file = $_GET['id'];
  $cari_data = mysqli_query($koneksi, "SELECT id_proses FROM tb_file
  inner join proses
  on proses.id_file = tb_file.id_file 
  where tb_file.id_file = ".$id_file);
  $lihatdata = mysqli_fetch_array($cari_data);
  $id_proses = $lihatdata['id_proses'];
} else {
    
      $sql2 = mysqli_query($koneksi, "SELECT * FROM proses ORDER BY id_proses DESC LIMIT 1");
      $data2 = mysqli_fetch_array($sql2);
      $id_proses = $data2['id_proses'];
      $dt = mysqli_query($koneksi, "SELECT nama_barang, count(nama_barang) as jumlah_brg from tb_databarang WHERE status = 'aktif' GROUP BY nama_barang");
      $dtq = mysqli_fetch_array($dt);
}
      
?>
<div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                        </tfoot>
                        <?php
                        $no = 1;
                        $in=0;
                        $valuein = $itemset1 = $lolosin = $jumlah1 = $support1 = $nama = $tlist = array();
                        $sql_insert .= "INSERT INTO itemset1 (id_proses, item, jumlah, support, lolos)";
                        $dt = mysqli_query($koneksi, "SELECT nama_barang, count(nama_barang) as jumlah_brg from tb_databarang 
                                        WHERE status = 'aktif' GROUP BY nama_barang ORDER BY no_transaksi ");
                        
                        while($d = mysqli_fetch_array($dt)){
                          $namabrg=$d['nama_barang'];
                          $jumlahbrg=$d['jumlah_brg'];
                          $support=number_format((($d['jumlah_brg']/$totaldata) * 100), 2);
                          $lolos = $support>=$min_support?"1":"0";
                          $valuein[] =" ('$id_proses', '$namabrg', '$jumlahbrg', '$support', '$lolos')";
                          $itemset1[]=$namabrg;
                          $support1[]=$support;
                          $lolosin[] = $lolos;
                          $jumlah1[]=$jumlahbrg;
                        ?>
                        <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $d['nama_barang']; ?></td>
                <td><?php echo $d['jumlah_brg']; ?></td> 
                <td><?php echo $support; ?></td>
                <td><?php echo($lolos==1?"Lolos":"Tidak Lolos"); ?></td>
                        </tr>
                        <?php 
                        $in++; }
                        // INSERT KE TABEL ITEMSET 1
                        if(!isset($_GET['id'])){
                $sql_insert .= " VALUES ";
                $sql_insert .= implode(',', $valuein);
                mysqli_query($koneksi, $sql_insert);
                        }
              ?>
            </table>
        </div>             
      
        <br><br><br>
      <!-- menampilkan itemset 1 yang Lolos -->
        <?php
        $count_itemset1_lolos = mysqli_query($koneksi, "SELECT count(lolos) as itemset1_lolos FROM itemset1 WHERE lolos = 1 and id_proses = $id_proses");
        $itemset1_lolos = mysqli_fetch_array($count_itemset1_lolos);
        $jumlah_itemset1_lolos = $itemset1_lolos['itemset1_lolos'];
        ?>
        <p>Itemset 1 yang lolos: <?php echo $itemset1_lolos['itemset1_lolos']; ?> item </p> 
        <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        $no_item1 = 1;
                        $ds_itemset1 = mysqli_query($koneksi, "SELECT a.item as item, a.support as support, a.lolos as lolos, a.id_proses as id_proses, a.jumlah as jumlah 
                        FROM itemset1 a INNER JOIN proses b ON a.id_proses = b.id_proses WHERE a.lolos = 1 and a.id_proses = '".$id_proses."' ORDER BY a.item");
                        
                        while($data_itemset1_lolos = mysqli_fetch_array($ds_itemset1)){
                        ?>
                        <tr>
                          <td><?php echo $no_item1; ?></td>
                          <td><?php echo $data_itemset1_lolos['item']; ?></td>
                          <td><?php echo $data_itemset1_lolos['jumlah']; ?></td> 
                          <td><?php echo $data_itemset1_lolos['support']; ?></td>
                          <td><?php echo ($data_itemset1_lolos['lolos']==1?"Lolos":"Tidak Lolos"); ?></td>
                        </tr>
                        <?php $no_item1++; } ?>
                        </tbody>
                    </table>
        </div>  
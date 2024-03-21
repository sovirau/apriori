<?php 
include('../config.php');
if(isset($_GET['id'])){
  $id_file = $_GET['id'];
  $cari_data = mysqli_query($koneksi,"SELECT * FROM tb_file
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
<br>
<div class="table-responsive">

        

        <table class="table table-bordered" id="dataTabled" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <!-- <th colspan ="2">Item1</th> -->
                          <th>Item1</th>
                          <th>Item2</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Item1</th>
                          <th>Item2</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                        </tfoot>
                    <?php
                    $nop = 1;
                    $in=0;
                    $valuein2 = $item1 = $item2 = $lolos2 = $jumlah2 = $support2 = array();
                    $sql_insert_itemset2 .= "INSERT INTO itemset2 (id_proses, item1, item2, jumlah, support, lolos)";
                    $sql_itemset2 = mysqli_query($koneksi, "SELECT CONCAT (b.item, ', ', a.item) as itemset2, a.item as item1, b.item as item2,
                    (select count(DISTINCT aa.nama_barang,aa.no_transaksi, bb.nama_barang, bb.no_transaksi) as jumlah_kombinasi from tb_databarang aa, tb_databarang bb 
                    WHERE aa.nama_barang = a.item and bb.nama_barang = b.item and aa.no_transaksi = bb.no_transaksi) as komb
                                                        from itemset1 a, itemset1 b
                                                        where b.item <> a.item and a.lolos = 1 and b.lolos = 1 and a.id_proses = '".$id_proses."' and b.id_proses = '".$id_proses."'
                                                        ");
                    
                    while($idua = mysqli_fetch_array($sql_itemset2)){
                      $namaitem1=$idua['item1'];
                      $namaitem2=$idua['item2'];
                      $jumlah_kombinasi=$idua['komb'];
                      $nilaisupport=number_format((($idua['komb']/$totaldata) * 100), 2);
                      $lolositem2 = $nilaisupport>=$min_support?"1":"0";
                      $valuein2[] =" ('$id_proses', '$namaitem1', '$namaitem2','$jumlah_kombinasi', '$nilaisupport', '$lolositem2')";
                      $item1[]=$namaitem1;
                      $item2[]=$namaitem2;
                      $support2[]=$nilaisupport;
                      $lolos2[] = $lolositem2;
                      $jumlah2[]=$jumlah_kombinasi;

                    ?>
                     <tr>
                      <td><?php echo $nop; ?></td>
                      <td><?php echo $namaitem1; ?></td>
                      <td><?php echo $namaitem2; ?></td>
                      <td><?php echo $jumlah_kombinasi; ?></td> 
                      <td><?php echo $nilaisupport; ?></td>
                      <td><?php echo ($lolositem2==1?"Lolos":"Tidak Lolos"); ?></td>
                    </tr>
                    <?php
                    $nop++; 
                    } if(!isset($_GET['id'])){
                    $sql_insert_itemset2 .= " VALUES ";
                    $sql_insert_itemset2 .= implode(',', $valuein2);
                    mysqli_query($koneksi, $sql_insert_itemset2); }
                    ?>
                    </table>            
</div>



            <?php 
                $sql_itemset2_lolos = mysqli_query($koneksi, "SELECT * FROM itemset2 WHERE lolos = 1 AND id_proses = '".$id_proses."'");
                $sql_jumlah_lolos2 = mysqli_query($koneksi, "SELECT count(lolos) as jumlah_lolos FROM itemset2 WHERE lolos = 1 AND id_proses = '".$id_proses."'");
                $q_itemset2_lolos = mysqli_fetch_array($sql_jumlah_lolos2);
                $count_itemset2_lolos = $q_itemset2_lolos['jumlah_lolos']; 
            ?>
                Itemset 2 yang lolos: <?php echo $count_itemset2_lolos; ?>  

    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Item1</th>
                          <th>Item2</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Item1</th>
                          <th>Item2</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        $n = 1;
                        while($data_itemset2_lolos = mysqli_fetch_array($sql_itemset2_lolos)){
                        ?>
                        <tr>
                          <td><?php echo $n; ?></td>
                          <td><?php echo $data_itemset2_lolos['item1']; ?></td>
                          <td><?php echo $data_itemset2_lolos['item2']; ?></td>
                          <td><?php echo $data_itemset2_lolos['jumlah']; ?></td> 
                          <td><?php echo $data_itemset2_lolos['support']; ?></td>
                          <td><?php echo ($data_itemset2_lolos['lolos']==1?"Lolos":"Tidak Lolos"); ?></td>
                        </tr>
                        <?php $n++; } ?>
                        </tbody>
        </table>
        </div>  
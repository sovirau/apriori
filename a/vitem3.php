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

    $i2_lolos = mysqli_query($koneksi, "SELECT count(lolos) as jumlah_lolos FROM itemset2 WHERE lolos = 1 AND id_proses = '".$id_proses."'");
    $lolos_item2 = mysqli_fetch_array($i2_lolos);
}
?>

<div class="table-responsive">
        <table class="table table-bordered" id="dataTabled" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <!-- <th colspan ="2">Item1</th> -->
                          <th>Item1</th>
                          <th>Item2</th>
                          <th>Item3</th>
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
                          <th>Item3</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                        </tfoot>

                    <?php
                    $nop = 1;
                    $in=0;
                    $valuein3 = $item1 = $item2 =$item3= $lolos3 = $jumlah3 = $support3 = array();
                    $sql_insert_itemset3 .= "INSERT INTO itemset3 (id_proses, item1, item2, item3, jumlah, support, lolos)";
                    $sql_itemset2 = mysqli_query($koneksi, "SELECT DISTINCT CONCAT (a.item1, ', ', b.item2, ', ', c.item2) as itemset3, a.item1 as item1, b.item2 as item2, c.item2 as item3,
                    (
select count(DISTINCT aa.nama_barang,aa.no_transaksi, bb.nama_barang, bb.no_transaksi, cc.nama_barang, cc.no_transaksi) as jumlah_kombinasi from tb_databarang aa, tb_databarang bb, tb_databarang cc
WHERE aa.nama_barang = a.item1 and bb.nama_barang = b.item2 and cc.nama_barang = c.item2 and aa.no_transaksi = bb.no_transaksi = cc.no_transaksi) as komb
from itemset2 a, itemset2 b, itemset2 c
                                                        where a.item1 < b.item2 and b.item2 < c.item2 and a.item1 < c.item2 and a.lolos = 1 and b.lolos = 1 and c.lolos = 1 and a.id_proses = '".$id_proses."' and b.id_proses = '".$id_proses."' and c.id_proses = '".$id_proses."'
                                                        ");
                    
                    while($itemke3 = mysqli_fetch_array($sql_itemset2)){
                      $namaitem1=$itemke3['item1'];
                      $namaitem2=$itemke3['item2'];
                      $namaitem3=$itemke3['item3'];
                      $jumlah_kombinasi=$itemke3['komb'];
                      $nilaisupport=number_format((($itemke3['komb']/$totaldata) * 100), 2);
                      $lolositem3 = $nilaisupport>=$min_support?"1":"0";
                      $valuein3[] =" ('$id_proses', '$namaitem1', '$namaitem2', '$namaitem3','$jumlah_kombinasi', '$nilaisupport', '$lolositem3')";
                      if($lolositem3){
                      $item1[]=$namaitem1;
                      $item2[]=$namaitem2;
                      $support3[]=$nilaisupport;
                      $lolos3[] = $lolositem3;
                      $jumlah3[]=$jumlah_kombinasi;
                      }                
                                        
                                        ?>
                                            <tr>
                                                <td><?php echo $nop; ?></td>
                                                <td><?php echo $namaitem1; ?></td>
                                                <td><?php echo $namaitem2; ?></td>
                                                <td><?php echo $namaitem3; ?></td>
                                                <td><?php echo $jumlah_kombinasi; ?></td> 
                                                <td><?php echo $nilaisupport; ?></td>
                                                <td><?php echo ($lolositem3==1?"Lolos":"Tidak Lolos"); ?></td>
                                            </tr>
                                        <?php
                                            $nop++;
                                        } if(!isset($_GET['id'])){
                                        $sql_insert_itemset3 .= " VALUES ";
                                        $sql_insert_itemset3 .= implode(',', $valuein3);
                                        mysqli_query($koneksi, $sql_insert_itemset3); }
                    ?>
                     
                    
                    </table>            
</div>

<?php 
                $sql_itemset3_lolos = mysqli_query($koneksi, "SELECT * FROM itemset3 WHERE lolos = 1 AND id_proses = '".$id_proses."'");
                $sql_jumlah_lolos3 = mysqli_query($koneksi, "SELECT count(lolos) as jumlah_lolos FROM itemset3 WHERE lolos = 1 AND id_proses = '".$id_proses."'");
                $q_itemset3_lolos = mysqli_fetch_array($sql_jumlah_lolos3);
                $count_itemset3_lolos = $q_itemset3_lolos['jumlah_lolos']; 
            ?>
                Itemset 3 yang lolos: <?php echo $count_itemset3_lolos; ?>  

    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Item1</th>
                          <th>Item2</th>
                          <th>Item3</th>
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
                          <th>Item3</th>
                          <th>Jumlah</th>
                          <th>Support</th>
                          <th>Keterangan</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        $n = 1;
                        while($data_itemset3_lolos = mysqli_fetch_array($sql_itemset3_lolos)){
                        ?>
                        <tr>
                          <td><?php echo $n; ?></td>
                          <td><?php echo $data_itemset3_lolos['item1']; ?></td>
                          <td><?php echo $data_itemset3_lolos['item2']; ?></td>
                          <td><?php echo $data_itemset3_lolos['item3']; ?></td>
                          <td><?php echo $data_itemset3_lolos['jumlah']; ?></td> 
                          <td><?php echo $data_itemset3_lolos['support']; ?></td>
                          <td><?php echo ($data_itemset3_lolos['lolos']==1?"Lolos":"Tidak Lolos"); ?></td>
                        </tr>
                        <?php $n++; } ?>
                        </tbody>
        </table>
        </div>  
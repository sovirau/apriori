<?php

if(isset($_GET['id'])){
    $id_file = $_GET['id'];
    $cari_data = mysqli_query($koneksi, "SELECT * FROM tb_file
    inner join proses
    on proses.id_file = tb_file.id_file 
    where tb_file.id_file = ".$id_file);
    $lihatdata = mysqli_fetch_array($cari_data);
    $id_proses = $lihatdata['id_proses'];
  } else {
$sql2 = mysqli_query($koneksi, "SELECT * FROM proses ORDER BY id_proses DESC LIMIT 1");
$data2 = mysqli_fetch_array($sql2);
$id_proses = $data2['id_proses'];
  }
?>

<div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Lift Ratio</th>
                          <th>Korelasi</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Lift Ratio</th>
                          <th>Korelasi</th>
                        </tr>
                        </tfoot>

<?php
$ne=1;
$nilai_confidence = $nilai_lift = $lolos_uji = $valuecon = array();
$sql_lr = mysqli_query($koneksi,"SELECT * from confidence where nilai_confidence >= '".$min_confidence."' and id_proses = ".$id_proses);
                            
                                while($row3 = mysqli_fetch_array($sql_lr)){ 
                                
                                ?>
                                          <tr>
                                            <td><?php echo $ne; ?></td>
                                            <td><?php echo $row3['item1']; ?> > <?php echo $row3['item2']; ?></td>
                                            <td><?php echo $row3['nilai_lift']; ?></td>
                                            <td><?php echo($row3['korelasi']=="pos"?"Korelasi Positif":"Korelasi Negatif"); ?></td>
                                          </tr>
                            <?php $ne++;    }
                            
                            ?>

                        <?php 
                        
              ?>
            </table>
        </div>            
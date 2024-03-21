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
}
?>

<div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Support Itemset</th>
                          <th>Support A</th>
                          <th>Support B</th>
                          <th>Confidence</th>
                          <th>Lift Ratio</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Itemset</th>
                          <th>Support Itemset</th>
                          <th>Support A</th>
                          <th>Support B</th>
                          <th>Confidence</th>
                          <th>Lift Ratio</th>
                        </tr>
                        </tfoot>

<?php
$sql_3 = mysqli_query($koneksi,"SELECT * FROM itemset3 WHERE lolos = 1 AND id_proses = ".$id_proses);
$itemset3_lolos = mysqli_num_rows($sql_3);
// if($itemset3_lolos > 0){

// }

$ne=1;
$nilai_confidence = $nilai_lift = $lolos_uji = $valuecon = array();
$sql_2 = mysqli_query($koneksi,"SELECT i2.item1 as item_1, i2.item2 as item_2, i2.support as support_itemset, i1.support as support_a, i3.support as support_b FROM itemset2 i2
                      inner join itemset1 i1
                      on i1.id_proses = i2.id_proses
                      and i1.item = i2.item1
                      inner join itemset1 i3
                      on i2.id_proses = i3.id_proses
                      and i3.item = i2.item2
                      WHERE i2.lolos = 1 AND i2.id_proses = ".$id_proses);
                            $itemset2_lolos = mysqli_num_rows($sql_2);
                            if($itemset2_lolos > 0){
                                $sql_in .= "INSERT INTO confidence (id_proses, item1, item2, korelasi, nilai_confidence, nilai_lift, support_antedecent, support_itemset) VALUES";
                                while($row2 = mysqli_fetch_array($sql_2)){ 
                                
                                $support_itemset = $row2['support_itemset'];
                                $support_a = $row2['support_a'];
                                $support_b = $row2['support_b'];
                                $confidence = number_format((($support_itemset/$row2['support_a'])*100), 2);
                                $lift = number_format((($row2['support_itemset']/$totaldata)/(($row2['support_a']/$totaldata)*($row2['support_b']/$totaldata))), 2);
                                $lolosc = $confidence>=$min_confidence?"1":"2";
                                $loloslr = $lift>=1?"pos":"neg";
                                $item_1 = $row2['item_1'];
                                $item_2 = $row2['item_2'];
                                $valuecon[] = " ('$id_proses', '$item_1', '$item_2', '$loloslr', '$confidence', '$lift', '$support_a','$support_itemset')"; 
                                $nilai_confidence[] = $confidence;
                                $nilai_lift[] = $lift;
                                $lolos_uji[] = $lolosc;
                                ?>
                                          <tr>
                                            <td><?php echo $ne; ?></td>
                                            <td><?php echo $row2['item_1']; ?> > <?php echo $row2['item_2']; ?></td>
                                            <td><?php echo $support_itemset; ?></td> 
                                            <td><?php echo $support_a; ?></td>
                                            <td><?php echo $support_b; ?></td>
                                            <td><?php echo $confidence; ?></td>
                                            <td><?php echo($lolosc=="1"?"Lolos":"Tidak Lolos"); ?></td>
                                          </tr>
                            <?php $ne++;    }
                            if(!isset($_GET['id'])){
                             $sql_in .= implode(',', $valuecon);
                             mysqli_query($koneksi, $sql_in);
                            }
                            }
                            ?>

                        <?php 
                        
              ?>
            </table>
        </div>            
<head>
<!-- <script type = "text/javascript" >
window.onbeforeunload = function() { return "Are you sure to leave this page?"; }; 
</script> -->
</head>
<?php
session_start();
require '../assets/vendor/autoload.php';

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Reader\Csv;
        use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if(isset($_POST['upload'])){
    $nama = $_POST['filebarang'];
    $dir = '../assets/excel/';
    $file = $_FILES['filebarang']['tmp_name'];
    $name = $_FILES['filebarang']['name'];
    $type = $_FILES['filebarang']['type'];

    if(isset($_GET['action']) && $_GET['action'] == 'add'){
    $sql = mysqli_query($koneksi, "INSERT INTO tb_file VALUES (DEFAULT, '$name')");

    $arr_file = explode('.', $_FILES['filebarang']['name']);
    $extension = end($arr_file);
 
        if ($extension == 'csv'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
            $spreadsheet = $reader->load($_FILES['filebarang']['tmp_name']);

            if(move_uploaded_file($file,$dir.$name)){
                mysqli_query($koneksi, "UPDATE tb_databarang SET status = 'non' WHERE status = 'aktif'");        
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                $sql_insert = "INSERT into tb_databarang (no_transaksi,nama_barang,harga_barang,total_barang,status) VALUES";
                for($i = 1;$i < count($sheetData);$i++)
                {
                    $no    = $sheetData[$i]['1'];
                    $nama  = $sheetData[$i]['2'];
                    $harga = $sheetData[$i]['3'];
                    $total = $sheetData[$i]['4'];
                    
                    $sql_insert .= " ('$no','$nama','$harga','$total', 'aktif'), ";
                } 
                $sql_insert = rtrim($sql_insert, ', ');
                mysqli_query($koneksi, $sql_insert);
                
                echo "<script>document.location='?pages=viewdata';</script>";
            } else {
                echo "<script>alert('Tidak Ada File'); document.location='?pages=dtbrg';</script>";
            }  
        }        
    }

$_SESSION['file'] = $name;
    
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Barang</h1>

                        <?php 
                        if($_GET['alert']=='a'){
                            echo '<div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span class="glyphicon glyphicon-ok"></span> Tambah Data Sukses</div>';
                        }elseif ($_GET['submit']=='b') {
                            echo '<div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span class="glyphicon glyphicon-ok"></span> Ubah Data Sukses</div>';
                        }elseif ($_GET['alert']=='c') {
                            echo '<div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span class="glyphicon glyphicon-ok"></span> Hapus Data Sukses</div>';
                        } ?>
</div>

            

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Input Data Barang</h6>
    </div>
    <div class="card-body">
    <div class="row">
    <div class = "col-6">
    <form method="post" enctype="multipart/form-data" action="?pages=dtbrg&action=add">
        <div class="form-group">
            <label for="exampleInputEmail1">Pilih File</label>
            <input name="filebarang" type="file" accept=".xls,.xlsx,.csv" required="required">
        </div>
        <div class="form-group">  
            <input class="btn btn-success" name="upload" value = "Import" type="submit">
        </div>
    </form>
    </div>
    </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Input Data Barang</h6>
    </div>
    <div class="card-body">
    <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th colspan="2">Nama File</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>No</th>
                      <th colspan="2">Nama File</th>
                    </tr>
                    </tfoot>
                    <?php
                    $no = 1;
                    $data = mysqli_query($koneksi, "select * from tb_file");
                    while($d = mysqli_fetch_array($data)){
                    ?>
                    <tr>
                      <td><?php echo $no++; ?></td>
                      <td><a href="?pages=dtbrg&action=view&id=<?php echo $d['id_file']; ?>"><?php echo $d['nama_file']; ?></a></td>
                      <td><a href="?pages=dtbrg&action=delete&id=<?php echo $d['id_file']; ?>" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')">HAPUS</a></td>
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
    } else { echo "<script language = javascript> alert('Data Gagal Dihapus'); document.location='?pages=dtbrg'; </script>"; }
    }

    if(isset($_GET['action']) && $_GET['action'] == 'view'){
        require '../assets/vendor/autoload.php';

        $sql .="INSERT into tb_databarang (no_transaksi,nama_barang,harga_barang,total_barang,status) VALUES ";

        $id_data = $_GET['id'];
        $datab = mysqli_query($koneksi, "SELECT * from tb_file where id_file = $id");
        $namafile = "../assets/excel/".$datab['nama_file'];
        $arrfile = explode('.', $namafile);
        $ext = end($arrfile);
 
        if ($extension == 'csv'){
            $r = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $r = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
            $spread = $r->load($namafile);

                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                for($i = 1;$i < count($sheetData);$i++)
                {
                    $no    = $sheetData[$i]['1'];
                    $nama  = $sheetData[$i]['2'];
                    $harga = $sheetData[$i]['3'];
                    $total = $sheetData[$i]['4'];
                    $sql .= " ('$no','$nama','$harga','$total', 'aktif'),";
                    $sql .= rtrim($sql, ",");
                    mysqli_query($koneksi, $sql);
                }
                echo "<script>document.location='?pages=viewdata';</script>";
            }
        
?>
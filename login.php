<div class="container">

<!-- Outer Row -->
<div class="row justify-content-center">

  <div class="col-xl-5 col-lg-12 col-md-9">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-12">
            <div class="p-5">
              <div class="text-center">
                    <?php 
                    if($_GET['action']=='failed'){
                        echo '<div class="alert alert-success alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <span class="glyphicon glyphicon-ok"></span> Gagal Login! Masukkan username dan password dengan benar!</div>';
                    } 
                    if($_GET['action']=='success'){
                        echo '<div class="alert alert-success alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <span class="glyphicon glyphicon-ok"></span> hore</div>';
                    }
                    ?>
                <h1 class="h4 text-gray-900 mb-4">Selamat Datang</h1>
              </div>
              <form action="" method="post">
                <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Masukkan Username" required>
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" required>
                </div>
                <hr>
                <input type="submit" name="log" value="Login" class="btn btn-primary btn-user btn-block">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>

</div>

<?php 
session_start();
include('config.php');
if(isset($_POST['log'])){
$username = $_POST['username'];
$password = md5($_POST['password']);

$q = mysqli_query($koneksi, "SELECT * from tb_user where username = '".$username."' and password = '".$password."'");
$cek = mysqli_fetch_assoc($q);
if($cek > 0){
  $_SESSION['username'] = $cek['username'];
  header("location:a/");
}else{
  header("location:?pages=def&action=failed");
}
}
?>

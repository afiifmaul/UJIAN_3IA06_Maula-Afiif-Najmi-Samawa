<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "akademik";

$conn =  new mysqli($host, $user, $pass, $dbname);

if($conn -> connect_error) {
    die("Koneksi Gagal: ".$conn->connect_error);
}
$nim = "";
$nama = "";
$jurusan = "";
$tingkat = "";
$alamat = "";
$error = "";
$sukses = "";

if(isset($_GET['op'])){
    $op = $_GET['op'];
}else{
    $op = "";
}

if($op == 'delete'){
    $nim = $_GET['nim'];
    $sql1 = "delete from mahasiswa where nim = '$nim'";
    $q1 = mysqli_query($conn,$sql1);
    if($q1){
        $sukses ="Berhasil hapus data";
    }else{
        $error="Gagal hapus data";
    }
}

if($op == 'edit'){
    $nim = $_GET['nim'];
    $sql1 = "select * from mahasiswa where nim = '$nim'";
    $q1 = mysqli_query($conn,$sql1);
    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $jurusan = $r1['jurusan'];
    $tingkat = $r1['tingkat'];
    $alamat = $r1['alamat'];

    if($nim == ''){
        $error = "Data tidak ditemukan";
    }
}



if(isset($_POST['simpan'])){ //untuk create
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $tingkat = $_POST['tingkat'];
    $alamat = $_POST['alamat'];

    if($nim && $nama && $jurusan && $tingkat && $alamat){
        if($op == 'edit'){ //untuk update
            $sql1 = "update mahasiswa set nim = '$nim',nama='$nama',jurusan='$jurusan',tingkat='$tingkat',alamat='$alamat' where nim = '$nim'";
            $q1 = mysqli_query($conn,$sql1);
            if($q1){
                $sukses = "Data berhasil diupdate";
            }else{
                $error = "Data gagal diupdate";
            }
        }else{ //untuk insert
            $sql1 = "insert into mahasiswa(nim,nama,jurusan,tingkat,alamat) values ('$nim','$nama','$jurusan','$tingkat','$alamat')";
            $q1 = mysqli_query($conn,$sql1);
            if($q1){
                $sukses = "Berhasil memasukkan data";
            }else{
                $error = "Gagal memasukkan data";
            }
        }
        
    }else{
        $error = "Semua field harus diisi";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {width: 800px;}
        .card {margin-top:  10px;}
        .card-header {
            background-color: blue; /* Blue background color */
            color: white; /* White text color */
        }

    </style>
</head>
<body>
    <div class="mx-auto">
        <!--untuk memasukkan data-->
        <div class="card">
            <div class="card-header">
                Form Pengisian
            </div>
            <div class="card-body">
                <?php
                if($error){
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh;url=index.php"); // 5 : detik
                }
                ?>
                <?php
                if($sukses){
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh;url=index.php"); // 5 : detik
                }
                ?>
                <form action="" method = "POST">
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim?>">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama?>">
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-control" id="jurusan">
                                <option value="">Pilih Jurusan</option>
                                <option value="Teknik Informatika" <?php if($jurusan == "Teknik Informatika") echo "selected"?>>Teknik Informatika</option>
                                <option value="Sistem Informasi"<?php if($jurusan == "Sistem Informasi") echo "selected"?>>Sistem Informasi</option>
                                <option value="Manajemen Informatika" <?php if($jurusan == "Manajemen Informatika") echo "selected"?>>Manajemen Informatika</option>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label for="tingkat" class="form-label">Tingkat</label>
                            <input type="text" class="form-control" id="tingkat" name="tingkat" value="<?php echo $tingkat?>">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat?>">
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-primary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jurusan</th>
                            <th scope="col">Tingkat</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        <tbody>
                            <?php
                            $sql2 = "select * from mahasiswa order by nim desc";
                            $q2 = mysqli_query($conn,$sql2);
                            $urut = 1;
                            while($r2 = mysqli_fetch_array($q2)){
                                $nim = $r2['nim'];
                                $nama = $r2['nama'];
                                $jurusan = $r2['jurusan'];
                                $tingkat = $r2['tingkat'];
                                $alamat = $r2['alamat'];

                                ?>
                                <tr>
                                    <th scope="row"><?php echo $urut++ ?></th>
                                    <td scope="row"><?php echo $nim ?></td>
                                    <td scope="row"><?php echo $nama ?></td>
                                    <td scope="row"><?php echo $jurusan ?></td>
                                    <td scope="row"><?php echo $tingkat ?></td>
                                    <td scope="row"><?php echo $alamat ?></td>
                                    <td scope="row">
                                        <a href="index.php?op=edit&nim=<?php echo $nim?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                        <a href="index.php?op=delete&nim=<?php echo $nim?>" onclick="return confirm('Hapus data?')"><button type="button" class="btn btn-danger">Delete</button></button></a>
                                        
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
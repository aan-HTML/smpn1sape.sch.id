<?php require_once 'auth.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = $conn->query("SELECT foto FROM galeri WHERE id=$id")->fetch_assoc();
if($row){
    if(!empty($row['foto'])){
        $path = "../assets/images/galeri/" . $row['foto'];
        if(file_exists($path)) unlink($path);
    }
    $conn->query("DELETE FROM galeri WHERE id=$id");
    alert('Foto berhasil dihapus!','success');
}
redirect('galeri.php');
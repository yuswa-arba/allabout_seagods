<?php


//konfigurasi file gambar
$typeGambar   = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');//type gambar yg di ijinkan
$ukuranMin    = 10240; //ukuran minimum, dlm byte
$ukuranMax    = 10485760; //ukuran maximum, dlm byte
//$folderTujuan = '../images/kategori/'; //folder dimana file akan di letakkan
//$folderThumbs = '../images/kategori/'; //folder dimana file thumbs akan di letakkan
//$lebarGambar  = 100; //ukuran lebar gambar hasil resize
$prefix       = 'thumb_';//nama awal utk file yg di resize
$prefixW       = 'w_';//nama awal utk file yg di resize

//Thumbnail without Watermark
function resizeGambar($pathFileTujuan,$lebarGambar,$folderThumbs,$namaFileTujuan,$prefix){
  $tipe = preg_replace("/.*\.(.*)$/","\\1",$pathFileTujuan);
  //@header("Content-type: image/".$tipe);
  
  list($lebar,$tinggi) = getimagesize($pathFileTujuan);
  $lebar_baru = $lebarGambar;
  $tinggi_baru= ($lebar_baru/$lebar) * $tinggi;
  // proses copy resize
  $gambar_baru = imagecreatetruecolor($lebar_baru, $tinggi_baru) or die('Problem In Creating image thumb');
  if($tipe=='jpg' || $tipe=='jpeg') {
     $gambar_asli = imagecreatefromjpeg($pathFileTujuan) or die('Problem In opening Source JPEG Image');
  } elseif($tipe=='gif') {
     $gambar_asli = imagecreatefromgif($pathFileTujuan) or die('Problem In opening Source GIF Image');
  } elseif($tipe=='png') {
     $gambar_asli = imagecreatefrompng($pathFileTujuan) or die('Problem In opening Source PNG Image');
  }
  imagecopyresampled($gambar_baru, $gambar_asli, 0, 0, 0, 0, $lebar_baru, $tinggi_baru, $lebar, $tinggi) or die('Problem In resizing');
 
  // Keluaran
  if($tipe=='jpg' || $tipe=='jpeg') {
     imagejpeg($gambar_baru, $folderThumbs.$prefix.$namaFileTujuan) or die('Problem In saving JPEG');
  } elseif($tipe=='gif') {
     imagegif($gambar_baru, $folderThumbs.$prefix.$namaFileTujuan) or die('Problem In saving GIF');
  } elseif($tipe=='png') {
     imagepng($gambar_baru, $folderThumbs.$prefix.$namaFileTujuan) or die('Problem In saving PNG');
  }
  //hapus resource gambar
  imagedestroy($gambar_baru);
  imagedestroy($gambar_asli);
}
//Thumbnail without Watermark
function resizeGambarProduk($pathFileTujuan,$lebarGambar,$folderThumbs,$namaFileTujuan,$prefix){
  $tipe = preg_replace("/.*\.(.*)$/","\\1","$pathFileTujuan");
  //@header("Content-type: image/".$tipe);
  
  list($lebar,$tinggi) = getimagesize("$pathFileTujuan");
  $lebar_baru = $lebarGambar;
  $tinggi_baru= ($lebar_baru/$lebar) * $tinggi;
  // proses copy resize
  $gambar_baru = imagecreatetruecolor($lebar_baru, $tinggi_baru) or die('Problem In Creating image thumb Produk');
  if($tipe=='jpg' || $tipe=='jpeg') {
     $gambar_asli = imagecreatefromjpeg($pathFileTujuan) or die('Problem In opening Source JPEG Image');
  } elseif($tipe=='gif') {
     $gambar_asli = imagecreatefromgif($pathFileTujuan) or die('Problem In opening Source GIF Image');
  } elseif($tipe=='png') {
     $gambar_asli = imagecreatefrompng($pathFileTujuan) or die('Problem In opening Source PNG Image');
  }
  imagecopyresampled($gambar_baru, $gambar_asli, 0, 0, 0, 0, $lebar_baru, $tinggi_baru, $lebar, $tinggi) or die('Problem In resizing');
 
  // Keluaran
  if($tipe=='jpg' || $tipe=='jpeg') {
     imagejpeg($gambar_baru, $folderThumbs.$prefix.$namaFileTujuan) or die('Problem In saving JPEG');
  } elseif($tipe=='gif') {
     imagegif($gambar_baru, $folderThumbs.$prefix.$namaFileTujuan) or die('Problem In saving GIF');
  } elseif($tipe=='png') {
     imagepng($gambar_baru, $folderThumbs.$prefix.$namaFileTujuan) or die('Problem In saving PNG');
  }
  //hapus resource gambar
  imagedestroy($gambar_baru);
  imagedestroy($gambar_asli);
}


 
function cekType($fileFoto,$typeGambar) {
    $typeFile = strtolower($fileFoto[type]);
  if(in_array($typeFile,$typeGambar)) {
    return true;
  } else {
    return false;
  }
}
function cekUkuran($fileFoto,$ukuranMin,$ukuranMax) {
  if($fileFoto[size] > $ukuranMin AND $fileFoto[size] < $ukuranMax) {
     return true;
  } else {
     return false;
  }
}

function namaFileTujuan($fileFoto,$folderTujuan){
$fileTujuan = $folderTujuan.$fileFoto['name'];
$namaFile = $fileFoto['name'];
  if(file_exists($fileTujuan)) { //jika nama file sudah ada, rename sesuai dengan urutan
    $i=0;
      while (file_exists($fileTujuan)) {
         $fileTujuan = $folderTujuan.$i.'_'.$fileFoto['name'];
         $namaFile = $i.'_'.$fileFoto['name'];
         $i++;
      }
  }
return $namaFile;
}

function namaFileTujuanProduk($fileFotoName,$folderTujuan){
$fileTujuan = $folderTujuan.$fileFotoName;
$namaFile = $fileFotoName;
  if(file_exists($fileTujuan)) { //jika nama file sudah ada, rename sesuai dengan urutan
    $i=0;
      while (file_exists($fileTujuan)) {
         $fileTujuan = $folderTujuan.$i.'_'.$fileFotoName;
         $namaFile = $i.'_'.$fileFotoName;
         $i++;
      }
  }
return $namaFile;
}

function prosesUpload($fileFoto,$pathFileTujuan) {
    if (move_uploaded_file($fileFoto['tmp_name'], $pathFileTujuan)) {
    return true;
  } else {
    return false;
    }
}

function prosesUploadProduk($fileFotoTmp,$pathFileTujuan) {
    if (move_uploaded_file($fileFotoTmp, $pathFileTujuan)) {
    return true;
  } else {
    return false;
    }
}
?>

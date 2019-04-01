<?php


//konfigurasi file gambar
$typeGambar   = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/JPG', 'image/JPEG');//type gambar yg di ijinkan
$ukuranMin    = 1240; //ukuran minimum, dlm byte
$ukuranMax    = 684805760; //ukuran maximum, dlm byte
//$folderTujuan = '../images/kategori/'; //folder dimana file akan di letakkan
//$folderThumbs = '../images/kategori/'; //folder dimana file thumbs akan di letakkan
//$lebarGambar  = 100; //ukuran lebar gambar hasil resize
$prefix       = 'thumb_';//nama awal utk file yg di resize
$prefixW       = 'w_';//nama awal utk file yg di resize

//Thumbnail without Watermark
function resizeGambar1($pathFileTujuan,$lebarGambar1,$folder1,$namaFileTujuan,$prefix){
  $tipe = preg_replace("/.*\.(.*)$/","\\1",$pathFileTujuan);
  //@header("Content-type: image/".$tipe);
  
  list($lebar1,$tinggi1) = getimagesize($pathFileTujuan);
  $lebar_baru1 = $lebarGambar1;
  $tinggi_baru1= ($lebar_baru1/$lebar1) * $tinggi1;
  // proses copy resize
  $gambar_baru1 = imagecreatetruecolor($lebar_baru1, $tinggi_baru1) or die('Problem In Creating image thumb');
  if($tipe=='jpg' || $tipe=='jpeg') {
     $gambar_asli1 = imagecreatefromjpeg($pathFileTujuan) or die('Problem In opening Source JPEG Image');
  } elseif($tipe=='JPG' || $tipe=='JPEG') {
     $gambar_asli1 = imagecreatefromJPEG($pathFileTujuan) or die('Problem In opening Source GIF Image');
  } elseif($tipe=='gif') {
     $gambar_asli1 = imagecreatefromgif($pathFileTujuan) or die('Problem In opening Source GIF Image');
  } elseif($tipe=='png') {
     $gambar_asli1 = imagecreatefrompng($pathFileTujuan) or die('Problem In opening Source PNG Image');
  }
  imagecopyresampled($gambar_baru1, $gambar_asli1, 0, 0, 0, 0, $lebar_baru1, $tinggi_baru1, $lebar1, $tinggi1) or die('Problem In resizing');
 
  // Keluaran
  if($tipe=='jpg' || $tipe=='jpeg') {
     imagejpeg($gambar_baru1, $folder1.$prefix.$namaFileTujuan) or die('Problem In saving JPEG');
  } elseif($tipe=='JPG' || $tipe=='JPEG') {
     imageJPEG($gambar_baru1, $folder1.$prefix.$namaFileTujuan) or die('Problem In saving GIF');
  } elseif($tipe=='gif') {
     imagegif($gambar_baru1, $folder1.$prefix.$namaFileTujuan) or die('Problem In saving GIF');
  } elseif($tipe=='png') {
     imagepng($gambar_baru1, $folder1.$prefix.$namaFileTujuan) or die('Problem In saving PNG');
  }
  //hapus resource gambar
}

function resizeGambar2($pathFileTujuan,$lebarGambar2,$folder2,$namaFileTujuan,$prefix){
  $tipe = preg_replace("/.*\.(.*)$/","\\1",$pathFileTujuan);
  //@header("Content-type: image/".$tipe);
  
  list($lebar2,$tinggi2) = getimagesize($pathFileTujuan);
  $lebar_baru2 = $lebarGambar2;
  $tinggi_baru2 = ($lebar_baru2/$lebar2) * $tinggi2;
  // proses copy resize
  $gambar_baru2 = imagecreatetruecolor($lebar_baru2, $tinggi_baru2) or die('Problem In Creating image thumb');
  if($tipe=='jpg' || $tipe=='jpeg') {
     $gambar_asli2 = imagecreatefromjpeg($pathFileTujuan) or die('Problem In opening Source JPEG Image');
  } elseif($tipe=='JPG' || $tipe=='JPEG') {
     $gambar_asli2 = imagecreatefromJPEG($pathFileTujuan) or die('Problem In opening Source JPEG Image');
  } elseif($tipe=='gif') {
     $gambar_asli2 = imagecreatefromgif($pathFileTujuan) or die('Problem In opening Source GIF Image');
  } elseif($tipe=='png') {
     $gambar_asli2 = imagecreatefrompng($pathFileTujuan) or die('Problem In opening Source PNG Image');
  }
  imagecopyresampled($gambar_baru2, $gambar_asli2, 0, 0, 0, 0, $lebar_baru2, $tinggi_baru2, $lebar2, $tinggi2) or die('Problem In resizing');
 
  // Keluaran
  if($tipe=='jpg' || $tipe=='jpeg') {
     imagejpeg($gambar_baru2, $folder2.$prefix.$namaFileTujuan) or die('Problem In saving JPEG');
  } elseif($tipe=='JPG' || $tipe=='JPEG') {
     imagejpeg($gambar_baru2, $folder2.$prefix.$namaFileTujuan) or die('Problem In saving JPEG');
  } elseif($tipe=='gif') {
     imagegif($gambar_baru2, $folder2.$prefix.$namaFileTujuan) or die('Problem In saving GIF');
  } elseif($tipe=='png') {
     imagepng($gambar_baru2, $folder2.$prefix.$namaFileTujuan) or die('Problem In saving PNG');
  }
  //hapus resource gambar
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

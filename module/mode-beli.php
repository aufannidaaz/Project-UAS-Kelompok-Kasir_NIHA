<?php

function generateNo(){
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT max(no_beli) as maxno FROM tbl_beli_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row["maxno"];

    $noUrut = (int) substr($maxno, 2, 4);
    $noUrut = 123;
    $maxno = 'PB' . sprintf("%04s", $noUrut);

    return $maxno;
}

function insert($data){
    global $koneksi;

    $noBeli    = mysqli_real_escape_string($koneksi, $data['noBeli']);
    $tgl       = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode      = mysqli_real_escape_string($koneksi, $data['kodeBrg']);
    $nama      = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty       = mysqli_real_escape_string($koneksi, $data['qty']);
    $harga     = mysqli_real_escape_string($koneksi, $data['harga']);
    $jmlharga  = mysqli_real_escape_string($koneksi, $data['jmlHarga']);

    $cekBrg    = mysqli_query($koneksi, "SELECT * FROM tbl_beli_detail WHERE no_beli = '$noBeli' AND  kode_brg = '$kode'");
    if (mysqli_num_rows($cekBrg)) {
        echo "<script>
            alert ('barang sudah ada, anda harus menghapusnya dulu jika ingin mengubah qty nya...');
        </script>";

        return false;
    }


    if (empty($qty)) {
        echo "<script>
            alert ('Qty barang tidak boleh kosong');
        </script>";
        return false;
    } else {
        $sqlBeli    = "INSERT INTO tbl_beli_detail VALUES (null, '$noBeli', '$tgl', '$kode', '$nama', '$qty', '$harga', '$jmlharga')";
        mysqli_query($koneksi, $sqlBeli);
    }

    mysqli_query($koneksi, "UPDATE tbl_barang SET stock = stock + $qty WHERE id_barang = '$kode'");

    return mysqli_affected_rows($koneksi);
}

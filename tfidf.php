<?php

include "koneksi.php";
$result = mysqli_query($koneksi,"SELECT term FROM stemming"); // ambil data semua dokumen
while( $row = mysqli_fetch_assoc( $result ) ) {
 $query[] = $row[ 'term' ];
}
$totalQuery = count($query);

$dokumen = mysqli_query($koneksi,"SELECT * FROM korpus ORDER BY id"); // ambil data semua dokumen
$total_dokumen = mysqli_num_rows($dokumen);  //ambil jumlah dokumen
$df = array(); // buat nyimpen df
$tf = array(); // buat nyimpen tf
$a = array();  // buat nyimpen satuan df (sebelum di total menjadi df)
$idf = array(); // buat nyimpen idf
$w = array(); // buat nyimpen bobot
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content />
    <meta name="author" content />
    <title>Sistem Temu Kembali Informasi</title>
    <link rel="icon" type="image/x-icon" href="css/image.png" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <main>
        <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
        <?php require_once('navbar.php') ?>
            <div class="container-fluid">
                <div class="page-header-content">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-10 text-center">
                            <h1 class="page-header-title mb-5">Sistem Temu Kembali Informasi</h1>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-n10">
            <div class="card mb-4">
                <div class="card-body">
                    <p class="py-2">Menghitung TF-IDF di Semua Dokumen</b></p>
                    <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Query</th>
                                <?php for ($i = 1; $i <=$total_dokumen; $i++) { ?>
                                <th><?php echo "D$i" ?></th>
                                <?php } ?>
                                <th>DF</th>
                                <th>D/DF</th>
                                <th>IDF</th>

                                <?php for ($i = 1; $i <=$total_dokumen; $i++) { ?>
                                <th><?php echo "WD$i" ?></th>
                                <?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for ($x = 0; $x <= $totalQuery-1; $x++) {
                                $keyword = $query[$x]; ?>
                            <tr>
                                <td><?php echo "$keyword"?></td>
                                <?php for ($i = 1; $i <=$total_dokumen; $i++) { 
                                $dokumenTerm = mysqli_query($koneksi,"SELECT * FROM stemming WHERE term = '$keyword' AND urutan = $i");
                                $totalDokumenTerm = mysqli_num_rows($dokumenTerm); // mencari jumlah term tiap dokumen
                                if ($totalDokumenTerm != 0){ //
                                    $a[$x][$i] = 1; //jika term di temukan maka satuan df 1 (sebelum ditotal menjadi df)
                                }else{
                                    $a[$x][$i] = 0;
                                }//
                                $tf[$x][$i] = $totalDokumenTerm; //total term tiap dokumen di simpan disini
                                ?>
                                <td>
                                    <?php echo "$totalDokumenTerm" ?>
                                </td>
                                <?php } ?>
                                <?php 
                                $total = array_sum($a[$x]);
                                if ($total != 0){
                                    $c = $total_dokumen/$total;
                                    $idf[$x] = log10($c); // rumus menghitung idf dan menyimpannya di array
                                }else {
                                    $c = 0 ;
                                    $idf[$x] = 0;
                                }
                                ?>
                                <td>
                                    <?php echo $total ?>
                                </td>
                                <td>
                                    <?php echo "$total_dokumen/$total" ?>
                                </td>
                                <td>
                                    <?php echo number_format((float)$idf[$x], 3, '.', ''); ?>
                                </td>
                                <?php
                                for ($i = 1; $i <=$total_dokumen; $i++) {
                                    $term = $tf[$x][$i]; // mengambil tf yang di simpan di array tadi
                                    $idef = $idf[$x]; // mengambil idf yang disimpan di array idf
                                    $wa = $term * $idef; // rumus TF-IDF
                                    $w[$x][$i] = $wa; // Menyimpan TF-IDF tiap dokumen di Array 
                                ?>
                                <td>
                                    <?php echo number_format((float)$wa, 3, '.', ''); ?>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
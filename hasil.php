<?php
include_once __DIR__."/VSMModule/Preprocessing.php";
include_once __DIR__."/VSMModule/VSM.php";
include_once 'koneksi.php';

    // == STEP 1 inisialisasi
    $preprocess = new Preprocessing();
    $vsm = new VSM();

    // == STEP 2 mendapatkan kata dasar
    $query = $preprocess::preprocess($_POST['cari']);

    // == STEP 3 medapatkan dokumen ke array
    $connect = mysqli_query($koneksi, "SELECT * FROM korpus");
    $arrayDokumen = [];
    while ($row = mysqli_fetch_assoc($connect)) {
        $arrayDoc = [
            'id_doc' => $row['id'],
            'dokumen' => implode(" ", $preprocess::preprocess($row['isi']))
        ];
        array_push($arrayDokumen, $arrayDoc);
    }
    // STEP 4 == mendapatkan ranking dengan VSM
    $rank = $vsm::get_rank($query, $arrayDokumen);
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
                    <p class="py-2">Query yang dimasukkan : <?php echo $_POST['cari'] ?></p>
                    <table id="example" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rank as $row) {
                            ?>
                            <tr>
                                <?php $id = $row["id_doc"];
                                $input = mysqli_query($koneksi,"SELECT * FROM korpus where id=$id");
                                $dok = mysqli_fetch_array($input);?>
                                <td><?php echo $row["ranking"]; ?></td>
                                <td><?php echo $dok["judul"]; ?> </td>
                                <td><?php echo substr($dok["isi"], 0, 300) . '...'; ?> </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            "order": [
                [0, "desc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: 0
            }, {
                orderable: false,
                targets: 1
            }, {
                orderable: false,
                targets: 2
            }],
            "paging": false,
            "searching": false,
        });
    });
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
</body>

</html>
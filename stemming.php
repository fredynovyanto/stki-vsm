<?php require_once('db.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content />
    <meta name="author" content />
    <title>Stemming</title>
    <link rel="icon" type="image/x-icon" href="css/image.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <main>
        <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
            <?php require_once('navbar.php') ?>
            <div class="container-fluid">
                <div class="page-header-content">
                    <div class="row justify-content-center mb-5">
                        <div class="col-md-12 d-flex justify-content-center">
                            <h1 class="page-header-title">
                                <span>Stemming</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-n10">
            <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Term</th>
                                        <th scope="col">Dokumen</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- List Data Menggunakan DataTable -->             
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

    <script>
    
        $(function(){
    
            $('.table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax":{
                        "url": "ajax/stemming.php?action=table_data",
                        "dataType": "json",
                        "type": "POST"
                        },
                "columns": [
                    { "data": "judul" },
                    { "data": "term" },
                    { "data": "dokumen" },
                ]  
    
            });
            });
    
    </script>
</body>

</html>
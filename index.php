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
                    <form class="page-header-signup mb-2 mb-md-0" action="hasil.php" method="POST">
                        <div class="form-row justify-content-center">
                            <div class="col-lg-10 col-md-8">
                                <div class="form-group mr-0 mr-lg-2">
                                    <input name="cari" class="form-control form-control-solid rounded-pill" type="text" placeholder="Tulis kata yang ingin dicari..." />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <button class="btn btn-primary bg-gradient-primary-to-secondary btn-block btn-marketing rounded-pill" type="submit" name="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
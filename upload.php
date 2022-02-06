<?php 
    require_once('koneksi.php');
    require_once('db.php');
    require_once('IDNStemmer.php');
    // include composer autoloader
    require_once __DIR__ . '/vendor/autoload.php';
    use Sastrawi\Stemmer\StemmerFactory;
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
        <?php 
            if (isset($_POST['submit'])) {
                $judul = trim($_POST['judul']);
                $isi = $_POST['isi'];
                $dokumen = $_POST['dokumen'];
                $urutan = $_POST['urutan']+1;
                // proses tokenisasi (memisah teks dokumen menjadi kata per kata)
                $karakter = preg_replace("/[^a-zA-Z]/", " ", $isi); // menghapus karakter yang tidak diperlukan, kecuali huruf dan angka
                $enter = trim(preg_replace('/\s+/', ' ', $karakter)); // menghilangkan spasi yang berlebihan menjadi hanya 1 spasi 
                $lower = strtolower($enter); // mengubah teks menjadi lower case
                $terms = explode(" ", $lower); // memisah kalimat menjadi sebuah array berdasarkan tanda pemisah 
                // input data asli ke tabel korpus
                $sql = "INSERT INTO korpus (judul, isi, dokumen) 
                        VALUES (:judul, :isi, :dokumen)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':judul' => $judul,
                    ':isi' => $isi,
                    ':dokumen' => $dokumen
                ]);
                // simpan data hasil tokenisasi ke tabel tokenisasi
                foreach($terms as $term){
                    $sql = "INSERT INTO tokenisasi (judul, term, dokumen) 
                        VALUES (:judul, :term, :dokumen)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':judul' => $judul,
                        ':term' => $term,
                        ':dokumen' => $dokumen
                    ]);
                }
                
                // proses filtering
                $stopwords = array('ada', 'bagaimanapun', 'berawal', 'bisakah', 'adalah', 'bagi', 'berbagai', 'boleh', 'adanya', 'bagian', 'berdatangan', 'bolehkah', 'adapun', 'bahkan', 'beri', 'bolehlah', 'agak', 'bahwa', 'berikan', 'buat', 'agaknya', 'bahwasanya', 'berikut', 'bukan', 'agar', 'baik', 'berikutnya', 'bukankah', 'akan', 'bakal', 'berjumlah', 'bukanlah', 'akankah', 'bakalan', 'berkali-kali', 'bukannya', 'akhir', 'balik', 'berkata', 'bulan', 'akhiri', 'banyak', 'berkehendak', 'bung', 'akhirnya', 'bapak', 'berkeinginan', 'cara', 'aku', 'baru', 'berkenaan', 'caranya', 'akulah', 'bawah', 'berlainan', 'cukup', 'amat', 'beberapa', 'berlalu', 'cukupkah', 'amatlah', 'begini', 'berlangsung', 'cukuplah', 'anda', 'beginian', 'berlebihan', 'cuma', 'andalah', 'beginikah', 'bermacam', 'dahulu', 'antar', 'beginilah', 'bermacam-macam', 'dalam', 'antara', 'begitu', 'bermaksud', 'dan', 'antaranya', 'begitukah', 'bermula', 'dapat', 'apa', 'begitulah', 'bersama', 'dari', 'apaan', 'begitupun', 'bersama-sama', 'daripada', 'apabila', 'bekerja', 'bersiap', 'datang', 'apakah', 'belakang', 'bersiap-siap', 'dekat', 'apalagi', 'belakangan', 'bertanya', 'demi', 'apatah', 'belum', 'bertanya-tanya', 'demikian', 'artinya', 'belumlah', 'berturut', 'demikianlah', 'asal', 'benar', 'berturut-turut', 'dengan', 'asalkan', 'benarkah', 'bertutur', 'depan', 'atas', 'benarlah', 'berujar', 'di', 'atau', 'berada', 'berupa', 'dia', 'ataukah', 'berakhir', 'besar', 'diakhiri', 'ataupun', 'berakhirlah', 'betul', 'diakhirinya', 'awal', 'berakhirnya', 'betulkah', 'dialah', 'awalnya', 'berapa', 'biasa', 'diantara', 'bagai', 'berapakah', 'biasanya', 'diantaranya', 'bagaikan', 'berapalah', 'bila', 'diberi', 'bagaimana', 'berapapun', 'bilakah', 'diberikan', 'bagaimanakah', 'berarti', 'bisa', 'diberikannya', 'dibuat', 'diperlihatkan', 'gunakan', 'jawabnya', 'dibuatnya', 'diperlukan', 'hal', 'jelas', 'didapat', 'diperlukannya', 'hampir', 'jelaskan', 'didatangkan', 'dipersoalkan', 'hanya', 'jelaslah', 'digunakan', 'dipertanyakan', 'hanyalah', 'jelasnya', 'diibaratkan', 'dipunyai', 'hari', 'jika', 'diibaratkannya', 'diri', 'harus', 'jikalau', 'diingat', 'dirinya', 'haruslah', 'juga', 'diingatkan', 'disampaikan', 'harusnya', 'jumlah', 'diinginkan', 'disebut', 'hendak', 'jumlahnya', 'dijawab', 'disebutkan', 'hendaklah', 'justru', 'dijelaskan', 'disebutkannya', 'hendaknya', 'kala', 'dijelaskannya', 'disini', 'hingga', 'kalau', 'dikarenakan', 'disinilah', 'ia', 'kalaulah', 'dikatakan', 'ditambahkan', 'ialah', 'kalaupun', 'dikatakannya', 'ditandaskan', 'ibarat', 'kalian', 'dikerjakan', 'ditanya', 'ibaratkan', 'kami', 'diketahui', 'ditanyai', 'ibaratnya', 'kamilah', 'diketahuinya', 'ditanyakan', 'ibu', 'kamu', 'dikira', 'ditegaskan', 'ikut', 'kamulah', 'dilakukan', 'ditujukan', 'ingat', 'kan', 'dilalui', 'ditunjuk', 'ingat-ingat', 'kapan', 'dilihat', 'ditunjuki', 'ingin', 'kapankah', 'dimaksud', 'ditunjukkan', 'inginkah', 'kapanpun', 'dimaksudkan', 'ditunjukkannya', 'inginkan', 'karena', 'dimaksudkannya', 'ditunjuknya', 'ini', 'karenanya', 'dimaksudnya', 'dituturkan', 'inikah', 'kasus', 'diminta', 'dituturkannya', 'inilah', 'kata', 'dimintai', 'diucapkan', 'itu', 'katakan', 'dimisalkan', 'diucapkannya', 'itukah', 'katakanlah', 'dimulai', 'diungkapkan', 'itulah', 'katanya', 'dimulailah', 'dong', 'jadi', 'ke', 'dimulainya', 'dua', 'jadilah', 'keadaan', 'dimungkinkan', 'dulu', 'jadinya', 'kebetulan', 'dini', 'empat', 'jangan', 'kecil', 'dipastikan', 'enggak', 'jangankan', 'kedua', 'diperbuat', 'enggaknya', 'janganlah', 'keduanya', 'diperbuatnya', 'entah', 'jauh', 'keinginan', 'dipergunakan', 'entahlah', 'jawab', 'kelamaan', 'diperkirakan', 'guna', 'jawaban', 'kelihatan', 'kelihatannya', 'maka', 'mempertanyakan', 'menuju', 'kelima', 'makanya', 'mempunyai', 'menunjuk', 'keluar', 'makin', 'memulai', 'menunjuki', 'kembali', 'malah', 'memungkinkan', 'menunjukkan', 'kemudian', 'malahan', 'menaiki', 'menunjuknya', 'kemungkinan', 'mampu', 'menambahkan', 'menurut', 'kemungkinannya', 'mampukah', 'menandaskan', 'menuturkan', 'kenapa', 'mana', 'menanti', 'menyampaikan', 'kepada', 'manakala', 'menantikan', 'menyangkut', 'kepadanya', 'manalagi', 'menanti-nanti', 'menyatakan', 'kesampaian', 'masa', 'menanya', 'menyebutkan', 'keseluruhan', 'masalah', 'menanyai', 'menyeluruh', 'keseluruhannya', 'masalahnya', 'menanyakan', 'menyiapkan', 'keterlaluan', 'masih', 'mendapat', 'merasa', 'ketika', 'masihkah', 'mendapatkan', 'mereka', 'khususnya', 'masing', 'mendatang', 'merekalah', 'kini', 'masing-masing', 'mendatangi', 'merupakan', 'kinilah', 'mau', 'mendatangkan', 'meski', 'kira', 'maupun', 'menegaskan', 'meskipun', 'kira-kira', 'melainkan', 'mengakhiri', 'meyakini', 'kiranya', 'melakukan', 'mengapa', 'meyakinkan', 'kita', 'melalui', 'mengatakan', 'minta', 'kitalah', 'melihat', 'mengatakannya', 'mirip', 'kok', 'melihatnya', 'mengenai', 'misal', 'kurang', 'memang', 'mengerjakan', 'misalkan', 'lagi', 'memastikan', 'mengetahui', 'misalnya', 'lagian', 'memberi', 'menggunakan', 'mula', 'lah', 'memberikan', 'menghendaki', 'mulai', 'lain', 'membuat', 'mengibaratkan', 'mulailah', 'lainnya', 'memerlukan', 'mengibaratkannya', 'mulanya', 'lalu', 'memihak', 'mengingat', 'mungkin', 'lama', 'meminta', 'mengingatkan', 'mungkinkah', 'lamanya', 'memintakan', 'menginginkan', 'nah', 'lanjut', 'memisalkan', 'mengira', 'naik', 'lanjutnya', 'memperbuat', 'mengucapkan', 'namun', 'lebih', 'mempergunakan', 'mengucapkannya', 'nanti', 'lewat', 'memperkirakan', 'mengungkapkan', 'nantinya', 'lima', 'memperlihatkan', 'menjadi', 'nyaris', 'luar', 'mempersiapkan', 'menjawab', 'nyatanya', 'macam', 'mempersoalkan', 'menjelaskan', 'oleh', 'olehnya', 'sama-sama', 'sedemikian', 'semacam', 'pada', 'sambil', 'sedikit', 'semakin', 'padahal', 'sampai', 'sedikitnya', 'semampu', 'padanya', 'sampaikan', 'seenaknya', 'semampunya', 'pak', 'sampai-sampai', 'segala', 'semasa', 'paling', 'sana', 'segalanya', 'semasih', 'panjang', 'sangat', 'segera', 'semata', 'pantas', 'sangatlah', 'seharusnya', 'semata-mata', 'para', 'satu', 'sehingga', 'semaunya', 'pasti', 'saya', 'seingat', 'sementara', 'pastilah', 'sayalah', 'sejak', 'semisal', 'penting', 'se', 'sejauh', 'semisalnya', 'pentingnya', 'sebab', 'sejenak', 'sempat', 'per', 'sebabnya', 'sejumlah', 'semua', 'percuma', 'sebagai', 'sekadar', 'semuanya', 'perlu', 'sebagaimana', 'sekadarnya', 'semula', 'perlukah', 'sebagainya', 'sekali', 'sendiri', 'perlunya', 'sebagian', 'sekalian', 'sendirian', 'pernah', 'sebaik', 'sekaligus', 'sendirinya', 'persoalan', 'sebaik-baiknya', 'sekali-kali', 'seolah', 'pertama', 'sebaiknya', 'sekalipun', 'seolah-olah', 'pertama-tama', 'sebaliknya', 'sekarang', 'seorang', 'pertanyaan', 'sebanyak', 'sekarang', 'sepanjang', 'pertanyakan', 'sebegini', 'sekecil', 'sepantasnya', 'pihak', 'sebegitu', 'seketika', 'sepantasnyalah', 'pihaknya', 'sebelum', 'sekiranya', 'seperlunya', 'pukul', 'sebelumnya', 'sekitar', 'seperti', 'pula', 'sebenarnya', 'sekitarnya', 'sepertinya', 'pun', 'seberapa', 'sekurang-kurangnya', 'sepihak', 'punya', 'sebesar', 'sekurangnya', 'sering', 'rasa', 'sebetulnya', 'sela', 'seringnya', 'rasanya', 'sebisanya', 'selain', 'serta', 'rata', 'sebuah', 'selaku', 'serupa', 'rupanya', 'sebut', 'selalu', 'sesaat', 'saat', 'sebutlah', 'selama', 'sesama', 'saatnya', 'sebutnya', 'selama-lamanya', 'sesampai', 'saja', 'secara', 'selamanya', 'sesegera', 'sajalah', 'secukupnya', 'selanjutnya', 'sesekali', 'saling', 'sedang', 'seluruh', 'seseorang', 'sama', 'sedangkan', 'seluruhnya', 'sesuatu', 'sesuatunya', 'tanya', 'tetap', 'sesudah', 'tanyakan', 'tetapi', 'sesudahnya', 'tanyanya', 'tiap', 'setelah', 'tapi', 'tiba', 'setempat', 'tegas', 'tiba-tiba', 'setengah', 'tegasnya', 'tidak', 'seterusnya', 'telah', 'tidakkah', 'setiap', 'tempat', 'tidaklah', 'setiba', 'tengah', 'tiga', 'setibanya', 'tentang', 'tinggi', 'setidaknya', 'tentu', 'toh', 'setidak-tidaknya', 'tentulah', 'tunjuk', 'setinggi', 'tentunya', 'turut', 'seusai', 'tepat', 'tutur', 'sewaktu', 'terakhir', 'tuturnya', 'siap', 'terasa', 'ucap', 'siapa', 'terbanyak', 'ucapnya', 'siapakah', 'terdahulu', 'ujar', 'siapapun', 'terdapat', 'ujarnya', 'sini', 'terdiri', 'umum', 'sinilah', 'terhadap', 'umumnya', 'soal', 'terhadapnya', 'ungkap', 'soalnya', 'teringat', 'ungkapnya', 'suatu', 'teringat-ingat', 'untuk', 'usah', 'sudah', 'terjadi', 'usai', 'sudahkah', 'terjadilah', 'waduh', 'sudahlah', 'terjadinya', 'wah', 'supaya', 'terkira', 'wahai', 'tadi', 'terlalu', 'waktu', 'tadinya', 'terlebih', 'waktunya', 'tahu', 'terlihat', 'walau', 'tahun', 'termasuk', 'walaupun', 'tak', 'ternyata', 'wong', 'tambah', 'tersampaikan', 'yaitu', 'tambahnya', 'tersebut', 'yakin', 'tampak', 'tersebutlah', 'yakni', 'tampaknya', 'tertentu', 'yang', 'tandas', 'tertuju', 'tandasnya', 'terus', 'tanpa', 'terutama');
                $filtering = array_diff($terms, $stopwords); //menghilangkan kata yang ada/sama distopword list
                // simpan data hasil filtering ke tabel filtering
                foreach($filtering as $filter){
                    $sql = "INSERT INTO filtering (judul, term, dokumen) 
                        VALUES (:judul, :term, :dokumen)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':judul' => $judul,
                        ':term' => $filter,
                        ':dokumen' => $dokumen
                    ]);
                    //proses stemming
                    $stemming = new IDNStemmer();
                    if(strlen($filter) > 5){
                        $filter = $stemming->doStemming($filter);
                    }
                    $sql1 = "INSERT INTO stemming (judul, term, dokumen, urutan) 
                        VALUES (:judul, :term, :dokumen, :urutan)";
                        $stmt = $pdo->prepare($sql1);
                        $stmt->execute([
                            ':judul' => $judul,
                            ':term' => $filter,
                            ':dokumen' => $dokumen,
                            ':urutan' => $urutan,
                        ]);
                }
            }
            // create stemmer
            // cukup dijalankan sekali saja, biasanya didaftarkan di service container
            // $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
            // $stemmer  = $stemmerFactory->createStemmer();
        ?>
        <div class="page-header pb-10 page-header-dark bg-gradient-primary-to-secondary">
        <?php require_once('navbar.php') ?>
            <div class="container-fluid">
                <div class="page-header-content">
                    <div class="row justify-content-center mb-5">
                        <div class="col-md-12 d-flex justify-content-center text-center">
                            <h1 class="page-header-title">
                                <span>Sistem Temu Kembali Informasi</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-n10">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="judul">Judul:</label>
                            <input name="judul" class="form-control" id="judul" type="text" required />
                        </div>
                        <div class="form-group">
                            <label for="isi">Isi:</label>
                            <textarea name="isi" id="isi" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="dokumen">Dokumen:</label>
                            <input name="dokumen" class="form-control" id="dokumen" type="text" required />
                        </div>
                        <?php 
                            $dokumen = mysqli_query($koneksi,"SELECT * FROM korpus ORDER BY id");
                            $total_dokumen = mysqli_num_rows($dokumen);
                        ?>
                        <input type="hidden" name="urutan" placeholder="urutan" value="<?php echo $total_dokumen ?>" />
                        <button name="submit" class="btn btn-primary mr-2 my-1" type="submit">Simpan</button>
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
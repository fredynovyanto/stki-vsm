<?php 
require_once('../db.php');

    if($_GET['action'] == "table_data"){

        $columns = array(  
                                0 => 'judul',
                                1 => 'term',
                                2 => 'dokumen',
                            );
    
        $querycount = $pdo->query("SELECT count(judul) as jumlah FROM tokenisasi");
        $datacount = $querycount->fetch(PDO::FETCH_ASSOC);
        
    
            $totalData = $datacount['jumlah'];
                
            $totalFiltered = $totalData; 
    
            $limit = $_POST['length'];
            $start = $_POST['start'];
            $order = $columns[$_POST['order']['0']['column']];
            $dir = $_POST['order']['0']['dir'];
                
            if(empty($_POST['search']['value']))
            {            
            $query = $pdo->query("SELECT judul,term,dokumen FROM tokenisasi order by $order $dir
                                                        LIMIT $limit
                                                        OFFSET $start");
            }
            else {
                $search = $_POST['search']['value']; 
                $query = $pdo->query("SELECT judul,term,dokumen FROM tokenisasi WHERE judul LIKE '%$search%'
                                                            or term LIKE '%$search%'
                                                            order by $order $dir
                                                            LIMIT $limit
                                                            OFFSET $start");
    
    
            $querycount = $pdo->query("SELECT count(judul) as jumlah FROM tokenisasi WHERE judul LIKE '%$search%'
                                                                            or term LIKE '%$search%'");
            $datacount = $querycount->fetch(PDO::FETCH_ASSOC);
            $totalFiltered = $datacount['jumlah'];
            }
    
            $data = array();
            if(!empty($query))
            {
                $no = $start + 1;
                while ($r = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $nestedData['judul'] = $r['judul'];
                    $nestedData['term'] = $r['term'];
                    $nestedData['dokumen'] = $r['dokumen'];
                    $data[] = $nestedData;
                    $no++;
                }
            }
            
            $json_data = array(
                        "draw"            => intval($_POST['draw']),  
                        "recordsTotal"    => intval($totalData),  
                        "recordsFiltered" => intval($totalFiltered), 
                        "data"            => $data  
                        );
                
            echo json_encode($json_data); 
    
    }
?>
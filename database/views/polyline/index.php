<nav class="navbar navbar-expand-sm navbar-light bg-default">
      <div class="container mt-3">
        <a class="btn btn-success" href="index.php?folder=polyline&file=formline">
        <i class="bi bi-plus-circle-fill"></i> Tambah</a>
          
            <form class="d-flex my-2 my-lg-0">
                <input type="hidden" name="folder" value="polyline">
                <input type="hidden" name="file" value="index">
                <input class="form-control me-sm-3" name="cari" value="<?php if(isset($cari)) echo $cari; ?>" type="text" placeholder="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="bi bi-search"></i></button>
            </form>
        
  </div>
</nav>

<div class="table-responsive-lg mt-3">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Street Name</th>
                <th scope="col">Geolocations</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            extract($_GET);
            $search = "";
            if(isset($cari)){
                $search = "WHERE name_streets like '%$cari%'";
            }
            $allDataLine= getAllData($table="streets", $where = $search);
            $no = 0;
            foreach ($allDataLine as $isi) {
                $no++;
            
            ?>
            <tr class="">
                <td scope="row"><?= $no ?></td>
                <td><?= $isi['name_streets'] ?></td>
                <td><?= $isi['geolocations_streets'] ?></td>
                <td>
                <a href="index.php?folder=polyline&file=index&id=<?= $isi['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin hapus : <?= $isi['name_streets'] ?>')"> <i class="bi bi-x-circle"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
    
    if(isset($id)) {
        delete($table="streets",$where ="id = '$id'");
        echo "<script>location='index.php?folder=polyline&file=index'</script>";
    }
?>

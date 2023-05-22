<?php
include 'conn.php';
include 'get_data.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.css" integrity="sha512-C4k/QrN4udgZnXStNFS5osxdhVECWyhMsK1pnlk+LkC7yJGCqoYxW4mH3/ZXLweODyzolwdWSqmmadudSHMRLA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <header>
        <div class="logo">

        </div>
        <div class="navbar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="col-md-3">
                            <select id="page" class="form-control">
                                <?php

                                $pages = $con->query("SELECT * FROM `epaper_images` where `epaper_id` = '$epaper_id'");
                                if ($pages->num_rows) {
                                    while ($page = $pages->fetch_assoc()) {
                                        $page_id = $page['page'];
                                        $url = 'view.php?date=' . $date . '&view=' . $epaper_id . '&page=' . $page_id;
                                        $selected = $activePage == $page_id ?  'selected' : '';
                                        echo "<option $selected data-url='$url' value='$page_id'>Page $page_id</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <nav aria-label="" style="margin-top:1rem;margin-left:1rem;">
                                <ul class="pagination">
                                    <?php
                                        $pages = $con->query("SELECT * FROM `epaper_images` where `epaper_id` = '$epaper_id'");
                                        $page_count = $pages->num_rows;
                                        
                                        if($activePage > 1 && $activePage > 0){
                                            $page_id = $activePage-1;
                                            $url = 'view.php?date=' . $date . '&view=' . $epaper_id . '&page=' . $page_id;
                                            echo '<li class="page-item">
                                                    <a class="page-link" href="'.$url.'">Previous</a>
                                                </li>
                                                ';
                                        }
                                        while($page = $pages->fetch_assoc()){
                                            $page_id = $page['page'];
                                            $url = 'view.php?date=' . $date . '&view=' . $epaper_id . '&page=' . $page_id;
                                            $active = $activePage == $page_id ?  'active' : '';
                                            echo '<li class="page-item '.$active.'"><a class="page-link" href="'.$url.'">'.$page_id.'</a></li>';
                                        }
                                        if($activePage < $page_count){
                                            $page_id = $activePage+1;
                                            $url = 'view.php?date=' . $date . '&view=' . $epaper_id . '&page=' . $page_id;
                                        echo '<li class="page-item">
                                                <a class="page-link" href="'.$url.'">Next</a>
                                            </li>';
                                        }
                                        
                                    ?>
                                    
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo $pdf; ?>" class="btn btn-info">Pdf</a>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-primary" id="clipBtn">Clip</a>
                            <a class="btn btn-dark">Archive</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main class="container">
        <div class="row">
            <section class="sidebar col-md-2">
                <?php
                $pages = $con->query("SELECT * FROM `epaper_images` where `epaper_id` = '$epaper_id'");
                if ($pages->num_rows) {
                    while ($p = $pages->fetch_assoc()) {
                        $page_id = $p['page'];
                        $url = 'view.php?date=' . $date . '&view=' . $epaper_id . '&page=' . $page_id;
                        ?>
                        <div style="cursor:pointer;" class="card" onclick="window.location.href='<?php echo $url;?>'">
                            <div class="card-body">
                                <img src="<?php echo $p['image']; ?>" style="height:10rem;width:100%;" alt="not found">
                            </div>
                            <div class="card-footer">
                                <p class="text-center">Page <?php echo $page_id;?></p>
                            </div>
                        </div>
                    <?php }
                } ?>
            </section>
            <section class="main col-md-10">
                <div class="alert alert-primary">
                    <?php echo date('d M Y',strtotime($date)); ?>
                    - Page
                    <?php echo $activePage;?>
                </div>
                <div class="cropper-container">
                    <img src="<?php echo  $image ?>" id="image">
                </div>
                
            </section>
        </div>
    </main>
    <footer>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-6lplKUSl86rUVprDIjiW8DuOniNX8UDoRATqZSds/7t6zCQZfaCe3e5zcGaQwxa8Kpn5RTM9Fvl3X2lLV4grPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>
    <script src="cropper.js"></script>
</body>

</html>
<?php
// $filePath = $_GET['path'];
$item = $_GET['file'];
// $content = file_get_contents($filePath);
// file_put_contents('./views/openfile.php', $content);
?>
<div>
    <div class="d-flex justify-content-start">
        <a href="./" class="btn btn-success">Back home</a>
    </div>
    <h3 class="text-center mt-5">Open file: <?= $item; ?></h3>
</div>
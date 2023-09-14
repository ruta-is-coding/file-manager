<?php
$path = $_GET['path'];

if (isset($_POST['file-name'])) {
    $fileName = $_POST['file-name'];
    $fullPath = $path . '/' . $fileName;
    //funkcija naujo failo sukūrimui
    fopen($fullPath, 'w+');
    header('Location: ?path=' . $path);
    exit;
};
?>

<div class="text-center">
    <h3 class="mb-3">Create a new file</h3>
    <p class="mb-5">File path <?php echo $path; ?></p>
    <form method="POST" class="mb-3">
        <input name="file-name" class="form-control form-control-lg" type="text" enctype="multipart/form-data" placeholder="Enter a file name with extension">
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-success">Create</button>
        </div>
    </form>
</div>
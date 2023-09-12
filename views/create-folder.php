<?php
$path = $_GET['path'];

if (isset($_POST['folder-name'])) {
    $folderName = $_POST['folder-name'];
    $fullPath = $path . '/' . $folderName;
    echo $folderName;
    mkdir($fullPath);
    header('Location: ?path=' . $path);
};
?>

<div class="text-center">
    <h3 class="mb-3">Create a new folder</h3>
    <p class="mb-5">Folder location: <?php echo $path; ?></p>
    <form method="POST" class="mb-3" enctype="multipart/form-data" action=<?php $fullPath ?>>
        <input name="folder-name" class="form-control form-control-lg" type="text" placeholder="Type the folder name">
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-success">Create</button>
        </div>
    </form>
</div>
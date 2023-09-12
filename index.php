<!-- Sukurkite failų valdymo sistemą.
 -->

<?php
//ar path parametras egzistuoja
$path = isset($_GET['path']) ? $_GET['path'] : ".";
//skenuojama path direktorija
$content = scandir($path);
//pašalinama . ir .. direktorijos iš masyvo, kai esame pradiniame puslapyje
unset($content[0]);
if ($path === ".") unset($content[1]);

// rekursinė funkcija masiniam ištrynimui
function remove_recursively($arrayToDelete)
{
    foreach ($arrayToDelete as $path) {
        if (is_dir($path)) {
            $content = scandir($path);
            foreach ($content as $item) {
                if ($item !== "." and $item !== "..") {
                    $fullItemPath = $path . '/' . $item;
                    //ištrynimas folderio viduje
                    if (is_dir($fullItemPath)) {
                        remove_recursively([$fullItemPath]); // Recursively remove directories
                        //jeigu tai ne folderis, o failas
                    } else {
                        unlink($fullItemPath);
                    }
                }
            }
            rmdir($path);
            // Neleidžia panaikinti index.php failo pagrindinėje direktorijoje
        } elseif ($path !== "./index.php") {
            unlink($path);
        }
    }
    // perkrovimas
    header("Location: ./");
}

//masinis ištrynimas
if (isset($_POST['id'])) {
    $arrayToDelete = $_POST['id'];
    remove_recursively($arrayToDelete);
}

//kai action parametras yra edit, atvaizduojame formą
if (isset($_GET['action']) and ($_GET['action']) === "edit" and isset($_GET['item'])) {
    $form = "<form method='POST' class='input-group my-2' style='width: 50%'>
    <input type='text' class='form-control' name='filename' placeholder='Enter file name'/>
    <button class='btn btn-success'>Save</button>
    </form>";
} else {
    $form = "";
}

//kai action parametras yra delete, ištriname failą
if (isset($_GET['action']) and ($_GET['action']) === "delete" and isset($_GET['item'])) {
    $deleteitem = $path . '/' . $_GET['item'];
    //negalima trinti index.php failo
    if ($deleteitem !== "./index.php") {
        if (is_dir($deleteitem)) {
            rmdir($deleteitem);
        } else {
            unlink($deleteitem);
        }
    }
    // perkrovimas
    header('Location: ?path=' . $path);
}

//puslapio query parametras
$page = isset($_GET['page']) ? $_GET['page'] : false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        a {
            color: black;
            text-decoration: none;
        }

        .container {
            max-width: 1024px;
        }
    </style>
</head>

<body>
    <div class="container pt-5">
        <?php
        //routeris
        switch ($page) {
            case 'new-file':
                include './views/create-file.php';
                break;
            case 'new-folder':
                include './views/create-folder.php';
                break;
            default:
                include './views/home.php';
        }
        ?>
</body>

</html>
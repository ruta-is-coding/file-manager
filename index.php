<!-- Sukurkite failų valdymo sistemą.
Veikiantis pavyzdys: https://tinyfilemanager.github.io/demo/
 -->
<?php
//ar path parametras egzistuoja
$path = isset($_GET['path']) ? $_GET['path'] : ".";
//skenuojama path direktorija
$content = scandir($path);
//masinis ištrynimas
if (isset($_POST['id'])) {
    $array = $_POST['id'];
    foreach ($array as $item) {
        if (is_dir($item)) {
            rmdir($item);
        } else {
            unlink($item);
        }
    }
    // perkrovimas
    header("Location: ./");
}
//pašalinama . direktorija ir .. direktorija iš masyvo, kai esame pradiniame puslapyje
unset($content[0]);
if ($path === ".") unset($content[1]);

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
    if (is_dir($deleteitem)) {
        rmdir($deleteitem);
    } else {
        unlink($deleteitem);
    }
    // perkrovimas
    header("Location: ./");
}
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
        <div class="d-flex justify-content-between mb-3">
            <button class="btn border border-black" onclick="selectAll(event)">Select all</button>
            <button href="#" class="btn btn-primary">New Item</button>
        </div>
        <!-- jeigu nėra užklausos parametro action, atvaizduojame formos atsidarantį elementą -->
        <?php
        if (!isset($_GET['action'])) :
        ?>
            <form method="post">
            <?php endif; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 30px" class="px-3">
                            <input type="checkbox" onclick="selectAll(event)" data-select>
                        </th>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($content as $item) {
                        //item informacija, pavadinimas, tikrasis failo kelias, failo dydis
                        $item_info = pathinfo($item);
                        $item_name = $item_info['basename'];
                        $realfile = "$path/$item_name";
                        $filesize = filesize($realfile);
                        //failo dydžio patikrinimas
                        $filesize = ($filesize >= 1048576) ? (round($filesize / 1024 / 1024) . " MB") : (($filesize >= 1024) ? (round($filesize / 1024) . " KB") : ($filesize = round(filesize($realfile)) . " B"));


                        //patikrinimas ar item turi extension
                        if (array_key_exists('extension', $item_info)) {
                            //extension patikrinimas ir ikonos klasės priskyrimas
                            switch ($item_info['extension']) {
                                case 'git':
                                    $file_icon_class = 'bi bi-github';
                                    break;
                                case 'php':
                                    $file_icon_class = 'bi bi-filetype-php';
                                    break;
                                case 'pdf':
                                    $file_icon_class = 'bi bi-file-earmark-pdf-fill';
                                    break;
                                case 'txt':
                                    $file_icon_class = 'bi bi-file-text';
                                    break;
                                case 'odt':
                                    $file_icon_class = 'bi bi-file-earmark-text';
                                    break;
                                case 'gif':
                                    $file_icon_class = 'bi bi-filetype-gif';
                                    break;
                                case 'mp4':
                                    $file_icon_class = 'bi bi-play-circle';
                                    break;
                                case 'mp3':
                                    $file_icon_class = 'bi bi-file-earmark-music';
                                    break;
                                case 'pptx':
                                    $file_icon_class = 'bi bi-filetype-pptx';
                                    break;
                                case ('jpeg' or 'jpg'):
                                    $file_icon_class = 'bi bi-image';
                                    break;
                            }
                            switch ($item_name) {
                                case '..':
                                    $file_icon_class = 'bi bi-arrow-up';
                                    $isUp = "";
                                    break;
                            }
                            //patikrinimas ar item yra direktorija (neturi extension)
                        } else if (is_dir($realfile)) {
                            $file_icon_class = "bi bi-folder";
                            // neatpažintas failo tipas
                        } else {
                            $file_icon_class = "bi bi-question-lg";
                        }

                        //patikrinimas ar item yra ne ..
                        if ($item_name !== "..") $isUp = "Folder";
                        //patikrinimas, ar item yra direktorija. Jei ne, prirašomas dydis.
                        $isFolder = is_dir($realfile) ? $isUp : $filesize;


                        //Nuoroda perėjimui į aukštesnę kategoriją
                        //Nuorodos direktorijoms
                        if ($item_name === ".." and $path !== ".") {
                            // $link="<a href='?path=$dir'>
                            $link = "<a href='?path=" . dirname($path) . "'>
                        <i class='$file_icon_class'></i>
                        $item_name
                        </a>";
                        } else {
                            $link = "<a href='?path=$path/$item_name'>
                        <i class='$file_icon_class'></i>
                        $item_name
                        </a>";
                        }

                        //patikriname, ar gavome duomenis iš redagavimo formos ir pervadiname item
                        if (isset($_POST['filename'])) {
                            //pirmas parametras - senojo failo kelias
                            //antras - naujo failo kelias
                            rename($realfile, $path . '/' . $_POST['filename']);
                            //redirektinimas į pradinį puslapį
                            header("Location: ./");
                        }

                        $result = "<tr>
                 <td class='px-3'>
                 <input type='checkbox' value='$realfile' name='id[]'>
                 </td>
                 <td>
                 $link";
                        $result .= (isset($_GET['item']) and $item === $_GET['item']) ? $form : '';
                        $result .= "             
                 </td>
                 <td>
                 $isFolder
                 </td> 
                 <td>
                 <a href='?action=edit&item=$item_name&path=$path'>
                 <i class='bi bi-pencil-square'></i>
                 </a>
                 <a href='?action=delete&item=$item_name&path=$path' class='ms-2'>
                 <i class='bi bi-trash3-fill'></i>
                 </a>
                 </td>
                 </tr>";

                        echo $result;
                    }
                    ?>
                </tbody>
            </table>
            <button class="btn btn-danger mt-2">Delete selected</button>
            <!-- jeigu nėra užklausos parametro action, atvaizduojame formos užsidarantį elementą -->
            <?php
            if (!isset($_GET['action'])) :
            ?>
            </form>
        <?php endif; ?>

    </div>

    <script>
        // Visų checkbox pažymėjimas
        function selectAll(e) {
            e.target.checked = !e.target.checked;
            document.querySelectorAll('input[type="checkbox"]').forEach(el => {
                el.checked = !el.checked;
            })
        }
    </script>
</body>

</html>
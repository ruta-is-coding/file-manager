<!-- Sukurkite failų valdymo sistemą.
Veikiantis pavyzdys: https://tinyfilemanager.github.io/demo/

Reikalavimai:
Kiekvieną direktorija turi turėti priskirtą nuorodą patekimui į ją. 
Kiekvienos direktorijos viduje turi būti nuoroda grįžimui atgal į aukštesnio lygio folderį.
Kiekvienoje direktorijoje turi būti galimybė sukurti naują failą ARBA naują folderį.
...
 -->
<?php
//ar path parametras egzistuoja
$path = isset($_GET['path']) ? $_GET['path'] : ".";
//skenuojama path direktorija
$content = scandir($path);
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
    unlink($path . '/' . $_GET['item']);
    //atnaujinimas
    $content = scandir($path);
    unset($content[0]);
    if ($path === ".") unset($content[1]);
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
        <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-primary mb-3">New Item</a>
        </div>
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
                    //item informacija ir pavadinimas
                    $item_info = pathinfo($item);
                    $item_name = $item_info['basename'];

                    // tikrasis failo kelias
                    $realfile = "$path/$item_name";
                    //failo dydis
                    $filesize = filesize($realfile);
                    //failo dydžio patikrinimas
                    $filesize = ($filesize >= 1048576) ? (round($filesize / 1024 / 1024) . " MB") : (($filesize >= 1024) ? (round($filesize / 1024) . " KB") : ($filesize = round(filesize($realfile)) . " B"));

                    //patikrinimas ar failas turi extension
                    if (array_key_exists('extension', $item_info)) {

                        //extension patikrinimas ir ikonos klasės priskyrimas
                        $file_icon_class = ($item_info['extension'] == "git") ? "bi bi-github" : (($item_info['extension'] == "php") ? "bi bi-filetype-php" : (($item_info['extension'] == "pdf") ? "bi bi-file-earmark-pdf-fill" : (($item_info['extension'] == "txt") ? "bi bi-file-text" : (($item_info['extension'] == "odt") ? "bi bi-file-earmark-text" : (($item_info['extension'] == "jpeg" or $item_info['extension'] == "jpg") ? "bi bi-image" : (($item_info['extension'] == "gif") ? "bi bi-filetype-gif" : (($item_info['extension'] == "mp4") ? "bi bi-play-circle" : (($item_info['extension'] == "mp3") ? "bi bi-file-earmark-music" : (($item_info['extension'] == "pptx") ? "bi bi-filetype-pptx" : (($item_name === "..") ? "bi bi-arrow-up" : ""))))))))));
                    } else if (is_dir($realfile)) {
                        $file_icon_class = "bi bi-folder";
                    } else {
                        $file_icon_class = "bi bi-question-lg";
                    }

                    //patikrinimas, ar direktorija yra ".."
                    $isUp = ($item_name === "..") ? "" : "Folder";
                    //patikrinimas, ar tai yra folderis. Jei ne, prirašomas dydis.
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
                 <input type='checkbox' value='$item_name' name='id[]'>
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
        <button class="btn mt-2 border border-black" onclick="selectAll(event)">Select all</button>
    </div>

    <script>
        // Visų checkbox pažymėjimas
        function selectAll(e) {
            e.target.checked = !e.target.checked;
            document.querySelectorAll('input[type="checkbox"]').forEach(el => {
                el.checked = !el.checked;
            })

            //KAIP ĮKELTI CHECKBOX'Ų FORMĄ, JEIGU JAU YRA VIDUJE EDITINIM'O FORMA?
            // document.querySelectorAll('input[name="id[]"]').forEach(el => {
            //     console.log(el.value);
            // })
        }
    </script>
</body>

</html>
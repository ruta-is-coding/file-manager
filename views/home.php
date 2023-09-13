<?php
$path = isset($_GET['path']) ? $_GET['path'] : ".";
?>
<div class="d-flex justify-content-end mb-3">
    <div class="d-flex gap-2">
        <?php
        echo '<a href="?page=new-file&path=' . $path . '" class="btn btn-success">New File</a>' .
            '<a href="?page=new-folder&path=' . $path . '" class="btn btn-warning">New Folder</a>';
        ?>
    </div>
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
                //paslėpimas
                if ($item !== "index.php" and $item !== "views") {
                    //item informacija, pavadinimas, tikrasis failo kelias, failo dydis
                    $item_info = pathinfo($item);
                    $realfile = "$path/$item";
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
                        switch ($item) {
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
                    if ($item !== "..") $isUp = "Folder";
                    //patikrinimas, ar item yra direktorija. Jei ne, prirašomas dydis.
                    $isFolder = is_dir($realfile) ? $isUp : $filesize;


                    //Nuoroda perėjimui į aukštesnę kategoriją
                    if ($item === ".." and $path !== ".") {
                        // $link="<a href='?path=$dir'>
                        $link = "<a href='?path=" . dirname($path) . "'>
                        <i class='$file_icon_class'></i>
                        $item
                        </a>";
                        //Nuorodos failams
                    } elseif (array_key_exists('extension', $item_info)) {
                        $link = "<a href='?page=open-file&file=$item&path=$realfile'>
                        <i class='$file_icon_class'></i>
                        $item
                        </a>";
                        //Nuorodos direktorijoms
                    } else {
                        $link = "<a href='?path=$path/$item'>
                        <i class='$file_icon_class'></i>
                        $item
                        </a>";
                    }

                    //patikriname, ar gavome duomenis iš redagavimo formos ir pervadiname item
                    if (isset($_POST['filename'])) {
                        //pirmas parametras - senojo failo kelias
                        //antras - naujo failo kelias
                        rename($oldFullPath, $path . '/' . $_POST['filename']);
                        // //redirektinimas į pradinį puslapį
                        header('Location: ?path=' . $path);
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
                 <a href='?action=edit&item=$item&path=$path&item=$item'>
                 <i class='bi bi-pencil-square'></i>
                 </a>
                 <a href='?action=delete&item=$item&path=$path' class='ms-2'>
                 <i class='bi bi-trash3-fill'></i>
                 </a>
                 </td>
                 </tr>";

                    echo $result;
                }
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
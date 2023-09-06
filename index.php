<!-- Sukurkite failų valdymo sistemą.
Veikiantis pavyzdys: https://tinyfilemanager.github.io/demo/

Reikalavimai:
Kiekvieną direktorija turi turėti priskirtą nuorodą patekimui į ją. 
Kiekvienos direktorijos viduje turi būti nuoroda grįžimui atgal į aukštesnio lygio folderį.
Kiekvienoje direktorijoje turi būti galimybė sukurti naują failą ARBA naują folderį.
...
 -->

 <?php
$path=isset($_GET['path'])?$_GET['path']:".";
//skenuojama direktorija
$content=scandir($path);
//pašalinama . direktorija iš masyvo
unset($content[0]);
//pašalinama .. direktorija iš masyvo, kai esame pradiniame puslapyje
if($path===".") unset($content[1]);
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />
    <style>
        a{
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container pt-5">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 30px">
                    <input type="checkbox">
                </th>
                <th>Name</th>
                <th>Size</th>
                <th>Modified</th>
                <th>Perms</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($content as $item){
                $item_info=pathinfo($item);
                $item_name=$item_info['basename'];
                //patikrinimas ar failas turi extension
                    if(array_key_exists('extension', $item_info)){
                    //ikonos klasės priskyrimas
                    //VISOMS IKONOMS TA PATI KLASĖ PRISKIRIAMA
                    if($item_info['extension'] == "git"){
                        $file_icon_class="bi bi-github";
                    } elseif($item_info['extension'] == "php"){
                        $file_icon_class="bi bi-filetype-php";
                    } elseif($item_info['extension'] == "txt"){
                        $file_icon_class="bi bi-file-text";
                    } elseif($item_info['extension'] == "jpeg"){
                        $file_icon_class="bi bi-image";
                    } elseif($item_info['extension'] == "mp4"){
                        $file_icon_class="bi bi-play-circle";
                    } elseif($item_info['extension'] == "mp3"){
                        $file_icon_class="bi bi-file-earmark-music";
                    } elseif($item_name===".."){
                        $file_icon_class="bi bi-arrow-up";
                    } else{
                        $file_icon_class="";
                    }
                } else{
                    $file_icon_class="bi bi-folder";
                }
                
                // tikrasis failo kelias
                $realfile="$path/$item_name";
                //failo dydis
                $filesize=filesize($realfile);
                //failo dydžio patikrinimas
                if($filesize>=1048576){
                    $filesize=round($filesize/1024/1024)." MB";
                }
                elseif($filesize>=1024){
                    $filesize=round($filesize/1024)." KB";
                } else{
                    $filesize=round(filesize($realfile))." B";
                }

                //patikrinimas ar direktorija yra .. (up)
                $isUp=($item_name==="..")?"":"Folder";
                //patikrinimas, ar item yra folderis
                $isFolder=is_dir($realfile)?$isUp: $filesize;

                //Perėjimui į aukštesnę kategoriją
                if($item_name===".." AND $path !=="."){
                    // $link="<a href='?path=$dir'>
                    $link = "<a href='?path=" . dirname($path) . "'>
                    <i class='$file_icon_class'></i>
                    $item_name
                    </a>";
                } else{
                    $link="<a href='?path=$path/$item_name'>
                    <i class='$file_icon_class'></i>
                    $item_name
                    </a>";
                }
                
                 echo "<tr>
                 <td> <input type='checkbox'></td>
                 <td>
                 $link
                 </td>
                 <td>
                 $isFolder
                 </td>
                 <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                 </tr>";
                }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>
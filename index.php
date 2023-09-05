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
$content=scandir($path);
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
                //patikrinimas, ar item yra folderis
                $isFolder=is_dir($item)?"Folder":"";
                if($item_name !== "." AND $item_name !== "..")
                 echo "<tr>
                 <td> <input type='checkbox'></td>
                 <td>
                 <a href='?path=$path/$item_name'>$item_name</a></td>
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
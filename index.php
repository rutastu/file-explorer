<?php
$path = isset($_GET['path']) ? $_GET['path'] : '.';

$files = scandir($path);

unset($files[0]);

if ($path === '.')
    unset($files[1]);

//priskiriame ikonas prie skirtingu extensionu
$iconMap = [
    'pdf' => 'bi bi-file-earmark-pdf',
    'png' => 'bi bi-image-alt',
    'mp3' => 'bi bi-file-earmark-music',
    'docx' => 'bi bi-file-earmark-word',
    'xlsx' => 'bi bi-file-earmark-excel',
    'mp4' => 'bi bi-person-video2',
    'jpg' => 'bi bi-file-earmark-image',
    'zip' => 'bi bi-file-earmark-zip',
    'txt' => 'bi bi-file-earmark-text',
    'pptx' => 'bi bi-file-earmark-slides',
    '' => 'bi bi-folder'
];

$fileExtension = '';
$icon = '';
$convertedFileSize = '';
$modifiedDate = '';

function convertedFileSize($size)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $unit = 0;
    while ($size >= 1024 && $unit < 4) {
        $size /= 1024;
        $unit++;
    }
    return round($size, 2) . ' ' . $units[$unit];
}


if (isset($_GET['action']) and ($_GET['action']) === "edit" and isset($_GET['item'])) {
    $fileToEdit = isset($_GET['item']) ? $_GET['item'] : '';
    $form = "<form method='POST' class='input-group my-1' style='width: 25%'>
    <input type='text' class='form-control' name='newFilename' placeholder='Modify file name'/>
    <input type='hidden' name='oldFilename' value='$fileToEdit'>
    <input type='hidden' name='editFile' value='1'>
    <button class='btn btn-success'>Save</button>
    </form>";
} else {
    $fileToEdit = '';
    $form = "";
}

if (isset($_POST['editFile'])) {
    $oldFilename = isset($_POST['oldFilename']) ? $_POST['oldFilename'] : '';
    $newFilename = isset($_POST['newFilename']) ? $_POST['newFilename'] : '';

    // Perform the file name update or rename operation here
    if ($oldFilename && $newFilename) {
        $oldFilePath = "$path/$oldFilename";
        $newFilePath = "$path/$newFilename";

        if (file_exists($oldFilePath) && !file_exists($newFilePath)) {
            rename($oldFilePath, $newFilePath);
        }
    }
    // Redirect back to the same page after processing the form
    header("Location: ?path=$path");
    exit;
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

        i {
            margin-right: 5px;
        }

        .checkbox {
            margin: 5px;
        }

        .first-column {
            width: 30px;
        }

        .modified-column {
            width: 200px;
        }

        .size-column {
            width: 100px;
        }

        .actions-column {
            width: 75px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="table table-sm table-success table-striped mt-5">
            <thead>
                <th class="first-column">
                    <input class="checkbox" type="checkbox" onclick="selectAll(event)">
                </th>
                <th>Name</th>
                <th class="modified-column">Modified</th>
                <th class="size-column">Size</th>
                <th class="actions-column">Actions</th>
            </thead>
            <tbody>
                <!-- pajungiame foreach cikla, kad atvaizduotume failus kaip sarasa, isskyrus index faila  -->
                <?php foreach ($files as $file) {
                    if ($file !== 'index.php') {
                        if ($file !== '..') {
                            //atvaizduojame failu dydzius
                            $filePath = "$path/$file";
                            $fileSize = filesize($filePath);
                            $convertedFileSize = convertedFileSize($fileSize);

                            //nustatome extension ir uzdedame atitinkama icon
                            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                            $icon = isset($iconMap[$fileExtension]) ? $iconMap[$fileExtension] : 'bi bi-file-earmark-medical';

                            //patikriname, kada failas buvo modifikuotas
                            $modifiedTimestamp = filemtime($filePath);
                            $modifiedDate = date('Y-m-d H:i:s', $modifiedTimestamp);
                        }

                        echo "<tr>
                    <td class=\"first-column\">" . ($file !== '..' ? "<input class=\"checkbox\" type='checkbox'>" : "") . "</td>
                    <td><i class=\"$icon\"></i> <a href=\"?path=$path/$file&action=edit&item=$file\">$file </a></td>
                    <td>$modifiedDate</td>
                    <td>$convertedFileSize</td>
                    <td>" . ($file !== '..' ? "<a href='?action=edit&item=$file&path=$path'><i class=\"bi bi-pencil-square\"></i></a>" : "")  . ($file !== '..' ? "<i class=\"bi bi-trash3\"></i>" : "") . "</td>
                    </tr>";
                    }
                    if ($file === $fileToEdit) {
                        echo "<tr>
                        <td></td>
                            <td colspan='5'>$form</td>
                            </tr>
                        ";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        // pasizymime visus checkboxus
        function selectAll(e) {
            e.target.checked = !e.target.checked;
            document.querySelectorAll('input[type="checkbox"]').forEach(el => {
                el.checked = !el.checked;
            })
        }
    </script>
</body>

</html>
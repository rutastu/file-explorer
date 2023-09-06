<?php
$path = isset($_GET['path']) ? $_GET['path'] : '.';

$files = scandir($path);

unset($files[0]);

if ($path === '.')
    unset($files[1]);

$iconMap = [
    'pdf' => 'bi bi-file-earmark-pdf',
    'png' => 'bi bi-filetype-png',
    'mp3' => 'bi bi-filetype-mp3',
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
    </style>
</head>

<body>
    <div class="container">
        <table class="table table-sm table-success table-striped">
            <thead class="table-light">
                <th>
                    <input type="checkbox">
                </th>
                <th>Name</th>
                <th>Size</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <?php foreach ($files as $file) {
                    if ($file !== 'index.php') {
                        if ($file !== '..') {
                            $filePath = "$path/$file";
                            $fileSize = filesize($filePath);
                            $convertedFileSize = convertedFileSize($fileSize);

                            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                            $icon = isset($iconMap[$fileExtension]) ? $iconMap[$fileExtension] : 'bi bi-file-earmark-medical';
                        }

                        echo "<tr>
                    <td> <input type='checkbox'<td>
                    <td><i class=\"$icon\"></i> <a href=\"?path=$path/$file\">$file</a></td>
                    <td>$convertedFileSize</td>
                    <td></td>
                    </tr>";
                    }
                }  //pajungiame foreach cikla, kad atvaizduotume failus kaip sarasa, isskyrus index faila 
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
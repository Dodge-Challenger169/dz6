<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $maxFileSize = 500 * 1024;
        $allowedExtensions = ['jpg', 'jpeg'];
        
        $fileName = $_POST['file_name'];
        $newWidth = $_POST['width'];
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if ($fileSize <= $maxFileSize && in_array($fileExtension, $allowedExtensions)) {
            list($originalWidth, $originalHeight) = getimagesize($fileTmpPath);
            $newHeight = (int) (($originalHeight / $originalWidth) * $newWidth);
            
            $image = imagecreatefromjpeg($fileTmpPath);
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            $newFileName = $fileName . '.jpg';
            imagejpeg($newImage, $newFileName);
            
            imagedestroy($image);
            imagedestroy($newImage);
            
            echo "Файл успешно загружен и изменен: <a href='$newFileName'>$newFileName</a>";
        } else {
            echo "Ошибка: файл не соответствует требованиям.";
        }
    } else {
        echo "Ошибка при загрузке файла.";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    Имя файла: <input type="text" name="file_name" required><br>
    Ширина изображения: <input type="number" name="width" required><br>
    Выберите файл: <input type="file" name="image" required><br>
    <input type="submit" value="Загрузить">
</form>

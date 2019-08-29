<?php
try
{
    // Get dimensions for specified images
    $isGridorCollag = $_REQUEST['isGridorCollag'];
    $user = $_REQUEST['id'];
    if ( $user == 0 || $user == '')
    {
        throw new Exception('User not found');
    }
    @mkdir('photos/' . $user);
    if ( $isGridorCollag == 'collag' )
    {
        $firstImage = $_REQUEST['firstImage'];
        $secondImage = $_REQUEST['secondImage'];
        if ( $firstImage == '' || $secondImage == '' )
        {
            throw new Exception('Images Id not found');
        }
        $filename_x = 'temp-pic/'. $firstImage . '.jpg';
        $filename_y = 'temp-pic/'. $secondImage . '.jpg';

        if ( $filename_x == '' || $filename_y == '' )
        {
            throw new Exception('Images not found');
        }
        
        $fileName = GenerateRandomString(16);
        $filename_result = 'photos/' . $user . '/' . $fileName . 'collag.jpg';
        
        list($width_x, $height_x) = getimagesize($filename_x);
        list($width_y, $height_y) = getimagesize($filename_y);
        
        // Create new image with desired dimensions
        
        $image = imagecreatetruecolor($width_x, $height_x + $height_x);
        $imagey = imagecreatefromjpeg($filename_y);
        $imagex = imagecreatefromjpeg($filename_x);
        
        // Load images and then copy to destination image
        
        $image_x = imagecreatefromjpeg($filename_x);
        $image_y = imagecreatefromjpeg($filename_y);
        
        imagecopyresampled($image, $imagex, 0, 0, 0, 0, $width_x, $height_x, $width_x, $height_x);
        @imagedestroy($imagex);
        imagecopyresampled($image, $imagey, 0, $height_x + 1, 0, 0, $width_x, $height_x, $width_x, $height_x);
        @imagedestroy($imagey);
        
        // Save the resulting image to disk (as JPEG)
        
        imagejpeg($image, $filename_result);
        
        // Clean up
        
        imagedestroy($image);
        imagedestroy($image_x);
        imagedestroy($image_y);
    }
    else 
    {
        $cardImage = $_REQUEST['cardImage'];
        if ( $cardImage == '' )
        {
            throw new Exception('cardimage id not found');
        }
        $filename_x = 'temp-pic/'. $cardImage . '.jpg';
        $filename_y = 'images/sampleicard.jpeg';

        if ( $filename_x == '' || $filename_y == '' )
        {
            throw new Exception('Images not found');
        }

        list($width_x, $height_x) = getimagesize($filename_x);
        list($width_y, $height_y) = getimagesize($filename_y);

        $image = imagecreatetruecolor($width_y, $height_y);
        $imagex = imagecreatefromjpeg($filename_x);
        $imagey = imagecreatefromjpeg($filename_y);


        $fileName = GenerateRandomString(16);
        $filename_result = 'photos/' . $user . '/' . $fileName . 'card.jpg';

        imagecopyresampled($image, $imagey, 0, 0, 0, 0, $width_y, $height_y, $width_y, $height_y);
        @imagedestroy($imagey);
        imagecopyresampled($image, $imagex, 185, 53, 0, 0, 85, 94, $width_x, $height_x);
        @imagedestroy($imagex);
        
        imagejpeg($image, $filename_result);

        // Clean up
        imagedestroy($image);
        imagedestroy($imagex);
    }

}
catch(Exception $e)
{
   echo 'Error:' . $e->getMessage();
}

function GenerateRandomString($len)
{
    static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ( $i = 0; $i < $len; $i++ )
    {
        $randstring .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randstring;
}

<?php
try
{
    // Get dimensions for specified images
    $isGridorCollag = $_REQUEST['isGridorCollag'];
    $user = $_REQUEST['id'];
    $collagAndCardOpt = $_REQUEST['collagAndCardOpt'];
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
        $distWidth = $width_x;
        $distHeight = $height_x * 2;
        $collageWidth = 0;
        $collageHeight = $height_x + 1;
        if ( $collagAndCardOpt != 'horizontal' )
        {
            $distWidth = $width_x * 2;
            $distHeight = $distHeight / 2;
            $collageWidth = $width_x + 1;
            $collageHeight = 0;
        }
        // Create new image with desired dimensions
        $image = imagecreatetruecolor($distWidth, $distHeight);

        // Load images and then copy to destination image
        $imagex = imagecreatefromjpeg($filename_x);
        $imagey = imagecreatefromjpeg($filename_y);

        // Load image 1 to destination image
        imagecopyresampled($image, $imagex, 0, 0, 0, 0, $width_x, $height_x, $width_x, $height_x);
        
        // Load image 2 to destination image
        imagecopyresampled($image, $imagey, $collageWidth, $collageHeight, 0, 0, $width_x, $height_x, $width_x, $height_x);
        
        // Save the resulting image to disk (as JPEG)
        imagejpeg($image, $filename_result);

        // Clean up
        @imagedestroy($imagex);
        @imagedestroy($imagey);
        @imagedestroy($image);
    }
    else if ( $isGridorCollag == 'card' )
    {
        $cardImage = $_REQUEST['cardImage'];
        if ( $cardImage == '' )
        {
            throw new Exception('cardimage id not found');
        }
        $filename_x = 'temp-pic/'. $cardImage . '.jpg';
        if ( $collagAndCardOpt == 'temp1' )
        {
            $filename_y = 'images/driving-licence-1.jpeg';
            $dstX = 185; $dstY = 53; $dstW = 85; $dstH = 94;
        } else {
            $filename_y = 'images/driving-licence-2.png';
            $dstX = 28; $dstY = 100; $dstW = 170; $dstH = 220;
        }
        if ( $filename_x == '' || $filename_y == '' )
        {
            throw new Exception('Images not found');
        }
        
        list($width_x, $height_x) = getimagesize($filename_x);
        list($width_y, $height_y) = getimagesize($filename_y);

        $image = imagecreatetruecolor($width_y, $height_y);
        $imagex = imagecreatefromjpeg($filename_x);
        if ( $collagAndCardOpt == 'temp1' )
        {
            $imagey = imagecreatefromjpeg($filename_y);
        } else {
            $imagey = imagecreatefrompng($filename_y);
        }

        $fileName = GenerateRandomString(16);
        $filename_result = 'photos/' . $user . '/' . $fileName . 'card.jpg';

        imagecopyresampled($image, $imagey, 0, 0, 0, 0, $width_y, $height_y, $width_y, $height_y);
        imagecopyresampled($image, $imagex, $dstX, $dstY, 0, 0, $dstW, $dstH, $width_x, $height_x);
        imagejpeg($image, $filename_result);
        
        @imagedestroy($imagex);
        @imagedestroy($imagey);
        @imagedestroy($image);        
    }
    else
    {
        $cardImage = $_REQUEST['cardImage'];
        if ( $cardImage == '' )
        {
            throw new Exception('calendar image id not found');
        }
        $filename_x = 'temp-pic/'. $cardImage . '.jpg';
        if ( $collagAndCardOpt == 'temp1' )
        {
            $filename_y = 'images/calendar-1.png';
            $dstX = 80; $dstY = 90; $dstW = 390; $dstH = 275;
        } else {
            $filename_y = 'images/calendar-2.png';
            $dstX = 60; $dstY = 14; $dstW = 352; $dstH = 420;
        }
        if ( $filename_x == '' || $filename_y == '' )
        {
            throw new Exception('Images not found');
        }
        list($width_x, $height_x) = getimagesize($filename_x);
        list($width_y, $height_y) = getimagesize($filename_y);

        $image = imagecreatetruecolor($width_y, $height_y);
        $imagex = imagecreatefromjpeg($filename_x);
        $imagey = imagecreatefrompng($filename_y);

        $fileName = GenerateRandomString(16);
        $filename_result = 'photos/' . $user . '/' . $fileName . 'calendar.jpg';
        $fileName = GenerateRandomString(16);
        $filename_result = 'photos/' . $user . '/' . $fileName . 'calendar.jpg';

        imagecopyresampled($image, $imagey, 0, 0, 0, 0, $width_y, $height_y, $width_y, $height_y);
        imagecopyresampled($image, $imagex, $dstX, $dstY, 0, 0, $dstW, $dstH, $width_x, $height_x);
        imagejpeg($image, $filename_result);

        @imagedestroy($imagey);
        @imagedestroy($imagex);
        @imagedestroy($image);
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

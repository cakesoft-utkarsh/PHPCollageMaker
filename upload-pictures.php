<?php
function RotateImage(Imagick $image)
{
    switch ($image->getImageOrientation()) {
    case Imagick::ORIENTATION_TOPLEFT:
        break;
    case Imagick::ORIENTATION_TOPRIGHT:
        $image->flopImage();
        break;
    case Imagick::ORIENTATION_BOTTOMRIGHT:
        $image->rotateImage("#000", 180);
        break;
    case Imagick::ORIENTATION_BOTTOMLEFT:
        $image->flopImage();
        $image->rotateImage("#000", 180);
        break;
    case Imagick::ORIENTATION_LEFTTOP:
        $image->flopImage();
        $image->rotateImage("#000", -90);
        break;
    case Imagick::ORIENTATION_RIGHTTOP:
        $image->rotateImage("#000", 90);
        break;
    case Imagick::ORIENTATION_RIGHTBOTTOM:
        $image->flopImage();
        $image->rotateImage("#000", 90);
        break;
    case Imagick::ORIENTATION_LEFTBOTTOM:
        $image->rotateImage("#000", -90);
        break;
    default: // Invalid orientation
        break;
    }
    $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
    return $image;
}
?>
<script type="text/javascript">
    function SubmitCropForm()
    {
        <?php if ( isset($_REQUEST['AspectRatio']) && $_REQUEST['AspectRatio'] ) { ?>
            if ( ( $('#x').val() == '' ) || ( $('#x').val() == null ) )
            {
                alert('Plese make a selection in the picture first.');
                return false;
            }
        <?php } ?>
        var formData = new FormData($('#frmCrop')[0]);
        doAjaxPostWithFile('upload-pictures.php?id=<?php echo $_REQUEST['id']; ?>&AspectRatio=<?php echo $_REQUEST['AspectRatio']; ?>&FrmId=<?php echo $_REQUEST['FrmId']; ?>&ImgId=<?php echo $_REQUEST['ImgId']; ?>&ImgSize=<?php echo $_REQUEST['ImgSize']; ?>', 'divUploadImage', '<div class="text-center"><img src="images/ajax-loading.gif" alt="Loading" /></div>', '<div class="text-center">Internet error</div>', null, formData);
        $('#mdlUploadPicture').modal('hide');
        return true;
    }
    function ValidateCropForm()
    {
        $('#mdlUploadPicture #btnSavePhoto').prop('disabled', true).css({'background': '#CCC', 'border': '1px solid #CCC'});
        <?php if ( isset($_REQUEST['AspectRatio']) && $_REQUEST['AspectRatio'] ) { ?>
            if ( ( $('#x').val() == '' ) || ( $('#x').val() == null ) )
            {
                return false;
            }
        <?php } ?>
        $('#mdlUploadPicture #btnSavePhoto').prop('disabled', false).css({'background': '#008000', 'border': '1px solid #008000'});
    }
    function SubmitUploadForm(imageName)
    {
        if ( ( imageName == '' ) || ( imageName == null ) )
        {
            alert('Plese select file.');
            return false;
        }
        var fileName = imageName;
        var ext = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
        if (    ( ext != "jpg" )
             && ( ext != "jpeg" )
             && ( ext != "png" )
             && ( ext != "gif" )
           )
        {
            alert('Only files of type .jpg, .jpeg, .png and .gif can be uploaded.');
            return false;
        }
        var formData = new FormData($('#frmUpload')[0]);
        doAjaxPostWithFile('upload-pictures.php?id=<?php echo $_REQUEST['id']; ?>&AspectRatio=<?php echo $_REQUEST['AspectRatio']; ?>&FrmId=<?php echo $_REQUEST['FrmId']; ?>&ImgId=<?php echo $_REQUEST['ImgId']; ?>&ImgSize=<?php echo $_REQUEST['ImgSize']; ?>', 'divUploadImage', '<div class="text-center"><img src="images/ajax-loading.gif" alt="Loading" /></div>', '<div class="text-center">Internet error</div>', null, formData);
    }
</script>
<div class="clear"><img src="images/spacer.gif" alt="" /></div>
<?php if ( 1 ) { echo '<div class="stripe-auto col-xs-12 col-sm-12"><span class="message1"></span></div>'; }?>
<?php
if ( empty($_POST) )
{
?>
    <div class="col-xs-12 col-sm-12">
        <form method="post" enctype="multipart/form-data" name="frmUpload" id="frmUpload">
            <input type="hidden" name="action" value="upload" />
            <div style="margin: 0 0 10px 0;">
                <label for="picture" class="btn btn-primary" data-tooltip="tooltip" data-placement="bottom" title="Max. upload limit (16MB)">Choose photo</label>
                <input id="picture" type="file" name="picture" onchange="SubmitUploadForm(this.value);" style="display: none" />
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#mdlUploadPicture #btnSavePhoto').hide();
            });
        </script>
    </div>
<?php
}
else
{
    switch ( $_POST['action'] )
    {
        case 'upload':
        {
            if ( !isset($_FILES['picture']) )
            {
                echo '<div class="stripe-auto"><span class="message1"><span style="color:red">Please select file.</span></span></div>';
                break;
            }
            if ( $_FILES['picture']['error'] )
            {
                echo '<div class="stripe-auto"><span class="message1"><span style="color:red">Invalid image. Please upload valid image file2.</span></span></div>';
                break;
            }
            $file = $_FILES['picture'];
            $tempPictureFileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (    ( $tempPictureFileExt != "jpg" )
                 && ( $tempPictureFileExt != "jpeg" )
                 && ( $tempPictureFileExt != "png" )
                 && ( $tempPictureFileExt != "gif" )
               )
            {
                echo '<div class="stripe-auto"><span class="message1"><span style="color:red">Only files of type .jpg, .jpeg, .png and .gif can be uploaded.</span></span></div>';
                break;
            }
            $tempGenerateName = GenerateRandomString(32);
            $tempPictureFileName = 'temp/' .  $tempGenerateName . '.' . $tempPictureFileExt;
            $tempPictureFileUrl = 'temp/' .  $tempGenerateName . '.' . $tempPictureFileExt;
            @unlink($tempPictureFileUrl);
            move_uploaded_file($file['tmp_name'], $tempPictureFileName);
            try
            {
                $img = new Imagick($tempPictureFileUrl);
                RotateImage($img);
                $img->stripImage();
                $img->writeImage();
                $dimensions = getimagesize($tempPictureFileUrl);
                $pictureWidth = $dimensions[0];
                $pictureHeight = $dimensions[1];
            }
            catch (Exception $e)
            {
                echo '<div class="stripe-auto"><span class="message1"><span style="color:red">Invalid image. Please upload valid image file.</span></span></div>';
                break;
            }
            $imgTag = '<div class="col-xs-7 col-sm-7 col-small-full-width extra-small-row"><img';
            $imgTag .= ' src="' . $tempPictureFileUrl . '" alt=""';
            if ( $pictureWidth > 330 )
            {
                $imgTag .= ' width="330px"';
                $scaleNumerator = $pictureWidth;
                $scaleDenominator = 320;
            }
            else
            {
                $scaleNumerator = 1;
                $scaleDenominator = 1;
            }
            $imgTag .= ' id="target"';
            $imgTag .= ' class="img-responsive cropImage"';
            $imgTag .= ' /></div>';
        ?>
            <div class="col-xs-12 col-sm-12">
                <p>Select area to show:</p>
                <form method="post" name="frmCrop" id="frmCrop" onsubmit="return SubmitCropForm();" class="row">
                    <input type="hidden" name="action" value="crop" />
                    <input type="hidden" name="ext" value="<?php echo $tempPictureFileExt;?>" />
                    <input type="hidden" name="tempGenerateName" value="<?php echo $tempGenerateName;?>" />
                    <input type="hidden" name="scaleNumerator" id="scaleNumerator" value="<?php echo $scaleNumerator;?>" />
                    <input type="hidden" name="scaleDenominator" id="scaleDenominator" value="<?php echo $scaleDenominator;?>" />
                    <input type="hidden" name="x" id="x" value="" />
                    <input type="hidden" name="y" id="y" value="" />
                    <input type="hidden" name="w" id="w" value="<?php echo ( isset($_REQUEST['AspectRatio']) && $_REQUEST['AspectRatio'] ) ? '' : $pictureWidth; ?>" />
                    <input type="hidden" name="h" id="h" value="<?php echo ( isset($_REQUEST['AspectRatio']) && $_REQUEST['AspectRatio'] ) ? '' : $pictureHeight; ?>" />
                    <?php echo $imgTag;?>
                </form>
            </div>
            <script type="text/javascript">
                function boxSelected(c)
                {
                    $('#x').val(c.x);
                    $('#y').val(c.y);
                    $('#w').val(c.w);
                    $('#h').val(c.h);
                    ValidateCropForm();
                }
                function boxUnselected()
                {
                    <?php if ( isset($_REQUEST['AspectRatio']) && $_REQUEST['AspectRatio'] ) { ?>
                        $('#x').val('');
                        $('#y').val('');
                        $('#w').val('');
                        $('#h').val('');
                    <?php } else { ?>
                        $('#x').val('');
                        $('#y').val('');
                        $('#w').val('<?php echo $pictureWidth; ?>');
                        $('#h').val('<?php echo $pictureHeight; ?>');
                    <?php } ?>
                    ValidateCropForm();
                }
                $(document).ready(function(){
                    $('#mdlUploadPicture #btnSavePhoto').show();
                    ValidateCropForm();
                    var jcrop_api;
                    $('#target').Jcrop({
                        <?php if ( isset($_REQUEST['AspectRatio']) && $_REQUEST['AspectRatio'] ) { echo 'aspectRatio: ' . $_REQUEST['AspectRatio'] . ','; } ?>
                        bgOpacity: 0.25,
                        onChange: boxSelected,
                        onSelect: boxSelected,
                        onRelease: boxUnselected
                    },function(){
                        Jcrop_api = this;
                    });
                    $("#mdlUploadPicture").on("hidden.bs.modal", function() {
                        $("#mdlUploadPicture").find('img').each(function(){
                            $(this).removeAttr('src', '');
                        });
                    });
                });
            </script>
        <?php
            break;
        }
        case 'crop':
        {
            $tempPictureFileUrl = '';
            $tempPicture = null;
            $tempPictureFileExt = $_REQUEST['ext'];
            $tempGenerateName = $_REQUEST['tempGenerateName'];
            $tempPictureFileName = 'temp/' . $tempGenerateName . '.' . $tempPictureFileExt;
            switch ( $tempPictureFileExt )
            {
                case 'jpg':
                case 'jpeg':
                    $tempPicture = imagecreatefromjpeg($tempPictureFileName);
                    break;
                case 'gif':
                    $tempPicture = imagecreatefromgif($tempPictureFileName);
                    break;
                case 'png':
                    $tempPicture = imagecreatefrompng($tempPictureFileName);
                    break;
            }
            if ( $_REQUEST['x'] == '' && $_REQUEST['y'] == '' )
            {
                $x = 0;
                $y = 0;
                $w = intval($_REQUEST['w']);
                $h = intval($_REQUEST['h']);
            }
            else
            {
                $scaleNumerator = intval($_REQUEST['scaleNumerator']);
                $scaleDenominator = intval($_REQUEST['scaleDenominator']);
                $x = intval($_REQUEST['x']) * $scaleNumerator / $scaleDenominator;
                $y = intval($_REQUEST['y']) * $scaleNumerator / $scaleDenominator;
                $w = intval($_REQUEST['w']) * $scaleNumerator / $scaleDenominator;
                $h = intval($_REQUEST['h']) * $scaleNumerator / $scaleDenominator;
            }
            do
            {
                $processedPictureRandomText = GenerateRandomString(16);
                $processedPictureFileName = 'temp-pic/' . $processedPictureRandomText . '.jpg';
                $processedPictureBigFileName = 'temp-pic-big/' . $processedPictureRandomText . '.jpg';
                if ( file_exists($processedPictureFileName) )
                {
                    continue;
                }
                if ( file_exists($processedPictureBigFileName) )
                {
                    continue;
                }
                break;
            }while(true);
            $ratio = $w / $h;
            $wPicture = 400;
            $hPicture = 400;
            if ( isset($_REQUEST['ImgSize']) && $_REQUEST['ImgSize'] )
            {
                $wPicture = 512;
                $hPicture = 512;
            }
            $wPictureBig = 1280;
            $hPictureBig = 1280;
            if ( $h > $w )
            {
                $hPicture = floor($wPicture / $ratio);
                $hPictureBig = floor($wPictureBig / $ratio);
            }
            else
            {
                $wPicture = floor($hPicture * $ratio);
                $wPictureBig = floor($hPictureBig * $ratio);
            }
            $processedPicture = ImageCreateTrueColor($wPicture, $hPicture);
            imagecopyresampled($processedPicture, $tempPicture, 0, 0, $x, $y, $wPicture, $hPicture, $w, $h);
            imagejpeg($processedPicture, $processedPictureFileName, 90);
            $processedPictureBig = ImageCreateTrueColor($wPictureBig, $hPictureBig);
            imagecopyresampled($processedPictureBig, $tempPicture, 0, 0, $x, $y, $wPictureBig, $hPictureBig, $w, $h);
            imagejpeg($processedPictureBig, $processedPictureBigFileName, 90);
            @unlink($tempPictureFileName);
            echo '
                <script type="text/javascript">
                    $(document).ready(function(){';
                        if ( isset($_REQUEST['FrmId']) && trim($_REQUEST['FrmId']) )
                        {
                            echo 'var ele1 = $(\'#' . $_REQUEST['FrmId'] . '\').find(\'#' . $_REQUEST['ImgId'] . 'picture-img\');
                            ele1.attr(\'src\', \'temp-pic/' . $processedPictureRandomText . '.jpg\');
                            var ele2 = $(\'#' . $_REQUEST['FrmId'] . '\').find(\'#' . $_REQUEST['ImgId'] . 'picture-input-' . $_REQUEST['id'] . '\');
                            ele2.val(\'' . $processedPictureRandomText . '\');
                            var ele1 = $(\'#' . $_REQUEST['FrmId'] . '\').find(\'#IsMediaAdded\').val(1).trigger(\'change\');';
                        }
                        else if ( isset($_REQUEST['id']) && trim($_REQUEST['id']) )
                        {
                            echo '$(\'#media-input-' . $_REQUEST['id'] . ' #' . $_REQUEST['ImgId'] . 'picture-img\').attr(\'src\', \'temp-pic/' . $processedPictureRandomText . '.jpg\');';
                            echo '$(\'#media-input-' . $_REQUEST['id'] . ' #' . $_REQUEST['ImgId'] . 'picture-input-' . $_REQUEST['id'] . '\').val(\'' . $processedPictureRandomText . '\');';
                            echo '$(\'#media-input-' . $_REQUEST['id'] . ' #IsMediaAdded\').val(1).trigger(\'change\');';
                        }
                        else
                        {
                            echo 'document.getElementById("' . $_REQUEST['ImgId'] . 'picture-img").src="' . 'temp-pic/' . $processedPictureRandomText . '.jpg' . '";
                            document.getElementById("' . $_REQUEST['ImgId'] . 'picture-input-'. $_REQUEST['id'] . '").value="' . $processedPictureRandomText . '";
                            document.getElementById("IsMediaAdded").value=1;
                            document.getElementById("IsMediaAdded").onchange();';
                        }
            echo '  });
                </script>'; 
            break;
        }
    }
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
?>

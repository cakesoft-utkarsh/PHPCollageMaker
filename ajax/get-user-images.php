<?php
try
{
    session_start();
    $userId =  $_GET['userid'];
    if ( $userId == 0 || $userId == '')
    {
        throw new Exception('User not found');
    }
    $images = scandir('../photos/' . $userId);
    $i = 0;
    if ($images) {
        foreach($images as $img) {
            if ($img == '.' || $img == '..') {
                continue;
            }
            $i++;
            ?>
            <div class="col-sm-6" style="margin-bottom: 20px;">
                <input type='checkbox' id="userimages['<?php echo $i; ?>']" name="userimages" value='<?php echo $i; ?>' style="margin-right:10px;" />
                <a href="javascript:void(0);" onclick="ImageBoxShow('<?php echo 'photos/' . $userId . '/' . $img; ?>', 0);" style="text-decoration:none;" title="">
                   <img src="<?php echo 'photos/' . $userId . '/' . $img; ?>" width='150px' height='150px' />
                </a>   
            </div>
            <?php
        }
    }
    if (!$i) {
        ?>
        <div class="col-sm-12">
            No images found.    
        </div>
        <?php
    }
}
catch(Exception $e)
{
   echo 'Error:' . $e->getMessage();
}
?>
<div id="mdlImageBox" class="modal fade" tabindex="1" role="dialog" style="z-index: 99999;">
    <div id="imgBoxModalWrapper" style="display: table; margin: 0 auto;">
        <div class="modal-dialog" role="document" style="padding-left: 10px; padding-right: 10px;" id="imgBoxModal">
            <div class="modal-content">
                <div class="modal-body" style="padding: 5px;">
                    <button type="button" class="close" data-dismiss="modal" style="margin-bottom: 10px;">&times;</button>
                    <img id="imgImageBox" class="img-responsive" alt="" src="" width="100%" />
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function ImageBoxShow(url, isHeightFixed)
    {
        $('#mdlImageBox #imgImageBox').attr('src', '');
        $('#mdlImageBox #imgImageBox').attr('src', url);
        if ( isHeightFixed )
        {
            $('#imgBoxModalWrapper').addClass('modal-vartically-center-wraper');
            $('#imgBoxModal').addClass('modal-vertically-center');
            $(".img-responsive").addClass("barcode-img");
        }
        else
        {
            $('#imgBoxModalWrapper').removeClass('modal-vartically-center-wraper');
            $('#imgBoxModal').removeClass('modal-vertically-center');
            $(".img-responsive").removeClass("barcode-img");
        }
        $('#mdlImageBox').modal();
        return false;
    }
    
        $("#mdlImageBox").on("hidden.bs.modal", function () {
        if ( $('#mdlFoodDetails, #mdlActivityDetails, #mdlProfileDetails').hasClass('in') )
        {
            $('body').addClass('modal-open');
        }
        $('#mdlImageBox #imgImageBox').attr('src', '');
    });
</script>
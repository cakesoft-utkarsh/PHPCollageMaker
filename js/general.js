function doAjaxPostWithFile(url, responseElementId, onLoading, onInternetError, callback, formData)
{
    if ( ( responseElementId != null ) && ( onLoading != null ) )
    {
        $('#' + responseElementId).html(onLoading);
    }
    $.ajax({
        type : 'POST',
        url : url,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success : function(response) {
            if ( responseElementId != null )
            {
                $('#' + responseElementId).html(response);
            }
            if ( callback )
            {
                callback();
            }
        },
        error : function() {
            if ( ( responseElementId != null ) && ( onInternetError != null ) )
            {
                $('#' + responseElementId).html(onInternetError);
            }
            if ( callback )
            {
                callback();
            }
        }
    });
    return false;
}

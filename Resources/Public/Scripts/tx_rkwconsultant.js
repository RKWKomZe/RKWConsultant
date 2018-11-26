
function removeFile(fileUid, consultantUid, fileNumber, fileType){

    // ajax params
    var params = {
        tx_rkwconsultant_rkwconsultantmyconsultant: {
            action : "removeFile",
            controller : "Consultant",
            pluginName : 'Rkwconsultantmyconsultant',
            fileUid : fileUid,
            consultantUid : consultantUid
        },
        type : 1449742214
    };

    // ajax request - Important: Send synchronous
    jQuery.ajax({
        url: 'index.php',
        data: params,
        async: false,
        success: function(result){

            // hide deleted file and show download field
            if (fileType == 'file') {
                // hide file and remove-button
                jQuery('.file' + fileNumber).hide();
                jQuery('.removeFile' + fileNumber).hide();
                // show upload field
                jQuery('.fileUpload' + fileNumber).css('visibility', 'visible');
            } else {
                // hide file and remove-button
                jQuery('.image' + fileNumber).hide();
                jQuery('.removeImage' + fileNumber).hide();
                // show upload field
                jQuery('.imageUpload' + fileNumber).css('visibility', 'visible');
            }

        },
        error: function(error) {
            console.log('Hier ist was fehlgeschlagen');
        }
    });

}

function removeContactPersonImage(fileUid, contactPersonUid, imageNumber){

    // ajax params
    var params = {
        tx_rkwconsultant_rkwconsultantmyconsultant: {
            action : "removeFile",
            controller : "ConsultantService",
            pluginName : 'Rkwconsultantmyconsultant',
            fileUid : fileUid,
            contactPersonUid : contactPersonUid
        },
        type : 1449742214
    };

    // ajax request - Important: Send synchronous
    jQuery.ajax({
        url: 'index.php',
        data: params,
        async: false,
        success: function(result){

            // hide file and remove-button
            jQuery('.image' + imageNumber).hide();
            jQuery('.removeImage' + imageNumber).hide();
            // show upload field
            jQuery('.imageUpload' + imageNumber).css('visibility', 'visible');

        },
        error: function(error) {
            console.log('Hier ist was fehlgeschlagen');
        }
    });

}

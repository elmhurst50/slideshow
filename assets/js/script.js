$(function() {

    var ul = $('#upload ul');

    $('#drop a').click(function() {
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({
        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function(e, data) {

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"' +
                    ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                    .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function() {

                if (tpl.hasClass('working')) {
                    jqXHR.abort();
                }

                tpl.fadeOut(function() {
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit().success(function(result, textStatus, jqXHR) {

                var ajaxReturn = JSON.parse(result);
                console.log(ajaxReturn);
                //make new DOM if file was an image, set animation
                if (ajaxReturn['image'] !== 'null') {
                    //get the position to place new elemnt for new image
                    var newPosition = $("#imageInfoForm").children().length;

                    //create area to append to update images information
                    //var display = createInfoForm(ajaxReturn['image'], newPosition, ajaxReturn['showId'], ajaxReturn['animation']);
                    var str = urldecode(ajaxReturn["html"]);
                    var returnHTML = str.replace(/~/g, newPosition);
                    $('#imageInfoForm').append(returnHTML);
                    warnOnClose();
                }

                //make hidden variable to pass as music file id.
                if (ajaxReturn['music'] !== 'null') {
                    $('#showMusic').val(ajaxReturn['music']);
                    $('#showMusicOnOff').val('on');
                    $('#musicFlash').hide();
                    $('#musicControls').css('display', 'block');
                    $('#labelMusic').text('Background Music');
                    var musicFile = 'uploads/' + ajaxReturn['showId'] + '/music/' + ajaxReturn['music']
                    $('#theMusic').attr('src', musicFile);
                    changeMusic(musicFile);
                    warnOnClose();
                }

                if (ajaxReturn['status'] === 'error') {
                    data.context.addClass('error');
                }

                setTimeout(function() {
                    data.context.fadeOut('slow');
                }, 3000);
            });
        },
        progress: function(e, data) {

            console.log(data);
            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if (progress == 100) {
                data.context.removeClass('working');


            }
        },
        fail: function(e, data) {
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


    //editing buttons for delete, move etc
    $('#imageInfoForm').on('click', '.deleteButton', function(e) {
        e.preventDefault();
        $(this).closest('.slideEditor').remove();
        warnOnClose();
    });

    function moveUp(ele) {
        var swapper = ele.closest('.slideEditor').prev(); // parent item to swap with
        var mover = ele.closest('.slideEditor'); // parent item to move up
        swapAttr(mover, swapper); //swap element values
        mover.insertBefore(swapper);//do the visual change
        warnOnClose();
    }

    function moveDown(ele) {
        var swapper = ele.closest('.slideEditor').next(); // parent item to swap with
        var mover = ele.closest('.slideEditor'); // parent item to move up
        swapAttr(mover, swapper); //swap element values
        mover.insertAfter(swapper);//do the visual change
        warnOnClose();
    }
    $('#imageInfoForm').on('click', '.upButton', function(e) {
        e.preventDefault();
        moveUp($(this));
    });
    $('#imageInfoForm').on('click', '.downButton', function(e) {
        e.preventDefault();
        moveDown($(this));
    });

    //if any changes made, add bind to alert user if they try to leave without saving
    $('input').on('change', function() {
        warnOnClose();
    });
    //unbind if submit button pressed
    $(':submit').on('click', function() {
        $(window).off('beforeunload');
    });

    //swap values between the request move up or down.
    function swapAttr(mover, swapper) {
        mover.title = mover.find("[name^=imageTitle]").val();
        mover.text = mover.find("[name^=imageText]").val();
        mover.id = mover.find("[name^=imageId]").val();
        swapper.title = swapper.find("[name^=imageTitle]").val();
        swapper.text = swapper.find("[name^=imageText]").val();
        swapper.id = swapper.find("[name^=imageId]").val();

        var temp = mover;
        mover.title = swapper.title;
        mover.text = swapper.text;
        mover.id = swapper.id;
        swapper.title = temp.title;
        swapper.text = temp.text;
        swapper.id = temp.id;

    }

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function(e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

    function warnOnClose() {
        $(window).on('beforeunload', function() {
            return 'You have unsaved changes, are you sure you want to leave?';
        });
    }

    function urldecode(str) {
        return decodeURIComponent((str + '').replace(/\+/g, '%20'));
    }

    function changeMusic(sourceUrl) {
        var audio = $("#showBackgroundMusic");
        $("#theMusic").attr("src", sourceUrl);
        /****************/
        audio[0].pause();
        audio[0].load();//suspends and restores all audio element
        /****************/
    }

    $('#musicForm').on('click', '#play', function() {
        var state = $(this).hasClass('playing');
        if (state !== true) {
            document.getElementById("showBackgroundMusic").play();
            $('#play').addClass('playing').text('Stop');
        } else {
            document.getElementById("showBackgroundMusic").pause();
            $('#play').removeClass('playing').text('Play');
        }
    });

    $('#onOff').click(function() {
        var state = $(this).hasClass('off');
        if (state !== true) {
            $(this).addClass('off').text('Off');
            $('#showMusicOnOff').val('off');
        } else {
            $(this).removeClass('off').text('On');
            $('#showMusicOnOff').val('on');
        }
    });

});
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="<?php echo __SITE_URL;?>/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo __SITE_URL;?>/assets/css/style.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script src="<?php echo __SITE_URL;?>/assets/js/cycle2.js"></script>


        <script>
            $(document).ready(function() {
                function settingsObj() {
                    this.animation = true;
                    this.transition = 'fade';
                    this.captions = true;
                    this.music = 'quiet';
                }
                var settings = new settingsObj();

                $('#index').click(function() {
                    $('.cycle-slideshow').cycle('goto', 4);
                });

                //save the settings to server
                $("#save-settings").click(function() {
                    console.log(settings);
                    $.ajax({
                        url: "save-settings.php",
                        type: "POST",
                        data: settings,
                        success: function(result) {
                            $('.modal').modal('hide');

                        }
                    });
                });


                //change animation settings
                $('#settings-animation button').click(function() {
                    $(this).siblings('.active').removeClass('active');
                    if ($(this).text() === "Off") {
                        settings.animation = false;
                    } else {
                        settings.animation = true;
                    }
                });

                //change transition settings
                $('#settings-transition button').click(function() {
                    $(this).siblings('.active').removeClass('active');
                    settings.transition = $(this).val();
                    if ($(this).text() === "Off") {
                        settings.transition = 'none';
                        $('.cycle-slideshow').on('cycle-before', function(event, slideOpts) {
                            slideOpts.speed = 50;
                            slideOpts.manualSpeed = 50;
                        });
                    } else {
                        settings.transition = 'fade';
                        $('.cycle-slideshow').on('cycle-before', function(event, slideOpts) {
                            slideOpts.speed = 2000;
                            slideOpts.manualSpeed = 1000;
                        });
                    }
                });

                //change text panel settings
                $('#settings-captions button').click(function() {
                    $(this).siblings('.active').removeClass('active');
                    if ($(this).text() === "Off") {
                        settings.captions = false;
                        $('.slide-panel').css('display', 'none');
                    } else {
                        settings.captions = true;
                        $('.slide-panel').css('display', 'block');
                    }
                });
                //change music settings
                $('#settings-music button').click(function() {
                    $(this).siblings('.active').removeClass('active');
                    settings.music = $(this).val();
                    changeVolume($(this).val());
                });

                //change the volume to requested
                function changeVolume(vol){
                    music = document.getElementById("background-music");
                    if(music){music.volume = vol;}
                }
                
                //update slide before show
                $('#slideshow-container').on('cycle-before', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
                    if (settings.animation === true) {
                        animation = $(incomingSlideEl).data('animation');
                        $(incomingSlideEl).addClass(animation);
                    }
                });

                //update slide after shown
                $('#slideshow-container').on('cycle-after', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
                    animation = $(outgoingSlideEl).data('animation');
                    $(outgoingSlideEl).removeClass(animation);
                });

                //pause button
                $('#pause').click(function(e) {
                    e.preventDefault();
                    var state = $('#pause').hasClass('pause');
                    if (state === true) {//if paused
                        changeVolume(0);
                        $('#slideshow-container').cycle('pause');
                        $('.slide').css('animation-play-state', 'paused');
                        $('.slide').css('webkit-animation-play-state', 'paused');
                        $(this).removeClass('pause').addClass('resume').find('.pauselabel').text('Play');
                        $(this).find('.glyphicon-pause').removeClass('glyphicon-pause').addClass('glyphicon-play');
                    } else {
                        changeVolume(settings.music);
                        $('#slideshow-container').cycle('resume');
                        $('.slide').css('animation-play-state', 'running');
                        $('.slide').css('webkit-animation-play-state', 'running');
                        $(this).removeClass('resume').addClass('pause').find('.pauselabel').text('Pause');
                        $(this).find('.glyphicon-play').removeClass('glyphicon-play').addClass('glyphicon-pause');
                    }
                });
        
            });
        </script>
    </head>
    <body class="playerScreen">
        <div class="wrapper">


            <?php $player->loadMusic(); ?>

            <div id="slideshow-container" class="cycle-slideshow" 
                 data-cycle-fx="fade"
                 data-cycle-prev="#prev"
                 data-cycle-next="#next"
                 data-cycle-speed="<?php echo $player->getTransition(); ?>" data-cycle-manual-speed="<?php echo $player->getManualTransition(); ?>"
                 data-cycle-timeout="4000"
                 data-cycle-loader=true
                 data-cycle-progressive="#slides"
                 data-cycle-slides="> div"

                 >

                <?php $player->loadInitialSlide(); ?>

                <script id="slides" type="text/cycle" data-cycle-split="---">
                        <?php $player->createMultipleSlides($player->numberofSlides(), 1); ?>
                </script>

            </div>
            <div id="player-menu" class="navbar-fixed-bottom">
                <a href=# class="pause" id="pause"><span class="glyphicon glyphicon-pause"></span><br /><span class="pauselabel">Pause</span></a>
                <a href=# id="prev"><span class="glyphicon glyphicon-backward"></span><br />Prev</a> 
                <a href=# id="next"><span class="glyphicon glyphicon-forward"></span><br />Next</a>
                <a href=#  class="" data-toggle="modal" data-target="#mapModal"><span class="glyphicon glyphicon-map-marker"></span><br />Location</a>
                <a href=#  class="" data-toggle="modal" data-target="#contactModal"><span class="glyphicon glyphicon-envelope"></span><br />Contact</a>
                <a href=#  class="" data-toggle="modal" data-target="#socialModal"><span class="glyphicon glyphicon-share"></span><br />Share</a>
                <a href="#" data-toggle="modal" data-target="#settingsModal"><span class="glyphicon glyphicon-cog"></span><br />Settings</a>
                <a href="<?php echo __SITE_URL;?>/admin"  id="index" class=""><span class="glyphicon glyphicon-home"></span><br />Home</a>

            </div>
        </div>

        
        <?php require_once 'modals/settings.php'; ?>

        <?php require_once 'modals/map.php'; ?>

        <?php require_once 'modals/contact.php'; ?>

        <?php require_once 'modals/social.php'; ?>
  </body>
</html>




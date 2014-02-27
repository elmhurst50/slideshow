<?php




echo $heading;
?>



<?php include 'partial/header.php'; ?>


<div class="container">

    <section class="col-md-3">
        <form id="upload" method="post" action="<?php echo __SITE_URL;?>/admin/upload" enctype="multipart/form-data">
            <div id="drop">
                Drop Here

                <a>Browse</a>
                <input type="hidden" name="showId" value="<?php echo $montage->id;?>" />
                <input type="file" name="upl" multiple />
                <input type="hidden" id="user" name="user" value="sam" />
            </div>

            <ul>
                <!-- The file uploads will be shown here -->
            </ul>

        </form>

        <div class="col-xs-12 panel">
            <h4>Upload File Here</h4>
            <p>Upload image files up to 1.5mb in JPG, JPEG, PNG and GIF formats.</p>
            <p>You upload background music files in mp3 format.</p>
        </div>
    </section>

    <section class="col-md-9">
        <form class="form-horizontal col-sm-12" id="slideshowForm" method="post" action="<?php echo __SITE_URL;?>/admin/save/<?php echo $montage->id;?>">
            <div class="row col-xs-12 list-item" id="showInfoForm">
                <div class="form-group col-md-12">
                    <label for="showTitle" class="col-sm-3 control-label">Show Title</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="showTitle" value="<?php echo empty($montage->showTitle) ? "" : $montage->showTitle; ?>" placeholder="Show Title">
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="showDesc" class="col-sm-3 control-label">Show Description</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="showDesc"><?php echo empty($montage->showDesc) ? "" : $montage->showDesc; ?></textarea>
                    </div>

                </div>

                <div class="form-group col-md-12">
                    <label for="showLocation" class="col-sm-3 control-label">Location</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="showLocation" value="<?php echo empty($montage->geo["format"]) ? "" : $montage->geo["format"]; ?>" placeholder="Location">
                    </div>
                </div>
            </div>



            <div id="musicForm" class="row col-xs-12 list-item">
                <div class="form-group col-md-12">
                    <label id="labelMusic" class="col-sm-3 control-label">Background music</label>
                    <p id="musicFlash"><?php echo empty($montage->showMusic) ? "No background music uploaded" : ""; ?></p>
                    <div id="musicControls" class="btn-group col-sm-5" style="display: <?php echo empty($montage->showMusic) ? "none" : "block"; ?>">
                        <button id="play" type="button" class="btn btn-primary" >Play</button>
                        <buttton id="onOff" type="button" class="btn btn-primary"><?php echo ($montage->showMusicOnOff === 'off') ? 'Off' : 'On'; ?></button>
                    </div>
                </div>
                <audio id="showBackgroundMusic">
                    <source id="theMusic" src="uploads/<?php echo $selectedShow . '/music/' . $montage->showMusic; ?>" type="audio/mpeg">
                </audio>
                <input type="hidden" name="showMusic" id="showMusic" value="<?php echo empty($montage->showMusic) ? "" : $montage->showMusic; ?>" />
                <input type="hidden" name="showMusicOnOff" id="showMusicOnOff" value="<?php echo empty($montage->showMusicOnOff) ? "" : $montage->showMusicOnOff; ?>" />

            </div>

            <section id="imageInfoForm">
                <?php
                $index = 0;
                foreach ($montage->slides as $val) {
                    ?>
                    <div class="row panel list-item slideEditor">
                        <div class="col-sm-2"><img src="<?php echo $val["image"]; ?>"  /></div>
                        <div class="col-sm-10">
                            <div class="form-group col-sm-12">
                                <label for="imageTitle_<?php echo $index; ?>" class="col-sm-3 control-label">Image Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="imageTitle_<?php echo $index; ?>" value="<?php echo $val["title"]; ?>" placeholder="Image Title">
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="imageText_<?php echo $index; ?>" class="col-sm-3 control-label">Image Description</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="imageText_<?php echo $index; ?>"  placeholder="Image Description"><?php echo $val["text"]; ?></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="imageId_<?php echo $index; ?>" value="<?php echo $val["image"]; ?>" />
                            <input type="hidden" name="animation_<?php echo $index; ?>" value="<?php echo $val["animation"]; ?>" />
                            <div class="col-sm-12 text-right">
                                <a href="#" class="btn deleteButton"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                <a href="#" class="btn upButton"><span class="glyphicon glyphicon-arrow-up"></span> Move Up</a>
                                <a href="#" class="btn downButton"><span class="glyphicon glyphicon-arrow-down"></span> Move Down</a>
                            </div>
                        </div>
                    </div>
                    <?php
                    $index++;
                    }
                    ?>
                </section>
                <input type="hidden" name="showId" value="<?php echo $montage->id; ?>" />

                <div class="col-xs-12 list-item text-right">
                    <input class="btn btn-primary" id="saveShow" name="saveShow" type="submit" value="Save" />
                    <input class="btn btn-primary" id="saveShow" name="saveShow" type="submit" value="Save & Watch" />
                </div>
            </form>
        </section>
    </div>

    <!-- JavaScript Includes -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo __SITE_URL;?>/assets/js/jquery.knob.js"></script>

    <!-- jQuery File Upload Dependencies -->
    <script src="<?php echo __SITE_URL;?>/assets/js/jquery.ui.widget.js"></script>
    <script src="<?php echo __SITE_URL;?>/assets/js/jquery.iframe-transport.js"></script>
    <script src="<?php echo __SITE_URL;?>/assets/js/jquery.fileupload.js"></script>

    <!-- Our main JS file -->
    <script src="<?php echo __SITE_URL;?>/assets/js/script.js"></script>




<?php
include 'partial/footer.php';

<?php
$index = new montageIndexFromFolder();

echo $heading;
?>



<?php include 'partial/header.php'; ?>

<div class="container">
    <div class="row panel col-xs-12">
        <a href="upload.php?show=new" class="btn btn-primary pull-left">Add New Montage</a>
        <p class="col-xs-6">Start your new montage slideshow</p>
    </div>
    <section class="row col-xs-12">
        <?php
        $slideshows = $index->allSlideshows();

        foreach ($slideshows as $showData) {
            ?>
            <div class="col-xs-12 list-item">
                <div class="col-sm-2"><img src="<?php echo $showData->image;?>" /></div>
                <div class="col-sm-8">
                    <div class="col-sm-10"><strong><?php echo $showData->showTitle; ?></strong></div>
                    <div class="col-sm-10"><?php echo $showData->showDesc; ?></div>
                    <div class="col-sm-10"><?php echo $showData->geo->format; ?></div>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-primary col-xs-12" href="player/archive/<?php echo $showData->id; ?>"><span class="glyphicon glyphicon-film"></span> Watch</a>
                    <a class="btn btn-primary col-xs-12" href="admin/edit/<?php echo $showData->id; ?>"><span class="glyphicon glyphicon-dashboard"></span> Edit</a>
                    <a class="btn btn-primary col-xs-12" href="admin/delete/<?php echo $showData->id; ?>"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                </div></div>
            <?php
        }
        ?>
    </section>
</div>


<?php
include 'partial/footer.php';

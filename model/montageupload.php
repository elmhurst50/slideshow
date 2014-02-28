<?php

use samjoyce\simpleimage\SimpleImage;

/*
 * Used mainly via ajax to upload images and music folders
 */

Class montageUpload {

    public function __construct() {
        $this->postData = $_POST;
        $this->montage = new MontageFromFolder($this->postData["showId"]);

        // A list of permitted file extensions
        $this->allowed = array('png', 'jpg', 'gif', 'mp3');

        //directory to save everything
        $this->dir = 'uploads/' . $this->postData["showId"];

        // run most of the class to save the files
        $this->saveFile();
    }

    /**
     * creates a thumb folder and places thumbs inside
     * @param string $original
     * @param type $folder
     * @param type $filename
     * @param type $width
     * @param type $height
     */
    public function createThumb($original, $filename, $folder = 'thumbs', $width = 200, $height = 150) {
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        $image = new SimpleImage();
        $image->load($original);
        $image->resize($width, $height);
        $image->save($folder . '/' . $filename);
    }

    /**
     * resize original image, then save in new filename
     * @param string  $original filename
     * @param string  $new filename
     * @param int $optWidth
     * @param int $optHeight
     * @return string ratio of the original image
     */
    public function saveOriginal($original, $new, $optWidth = 1920, $optHeight = 1080) {
        $image = new SimpleImage();
        $image->load($original);
        if ($image->getWidth() > $optWidth) {//resize if image is massive
            $image->resizeToWidth($optWidth);
        }
        $image->save($new);

        return $image->getRatio();
    }

    /**
     * Save the music file
     * @param string $original filename of music
     * @param string $folder destination folder
     * @param string $filename new filename
     */
    public function saveMusic($original, $filename, $folder = 'music') {
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        move_uploaded_file($original, $folder . '/' . $filename);
    }

    /**
     * check the file type, save appropriately
     */
    public function saveFile() {
        if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {

            $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

            //exit if not allowed extension
            if (!in_array(strtolower($extension), $this->allowed)) {
                echo '{"status":"error, file type not allowed"}';
                exit;
            }

            if ($extension == 'mp3') {//save music
                $this->saveMusic($_FILES['upl']['tmp_name'], $this->postData["showId"] . '.mp3', $this->dir . '/music');
                $this->sendMusicAjaxJsonResult();
            } else {//save image
                $this->newImageFilename = uniqid() . '.' . $extension;
                $ratio = $this->saveOriginal($_FILES['upl']['tmp_name'], $this->dir . '/' . $this->newImageFilename);
                $this->createThumb($this->dir . '/' . $this->newImageFilename, $this->newImageFilename, $this->dir . '/thumbs');
                $this->animation = $ratio > 0.65 ? "scrollUp" : "zoom";
                $this->sendImageAjaxJsonResult();
            }
        }
    }

    /**
     * display the json result to be read by javascript
     */
    public function sendImageAjaxJsonResult() {
        $html = $this->montage->createEdit('~', $this->postData["showId"], $val = array("title" => "", "text" => " ", "image" => __SITE_URL . '/' . $this->dir . '/' . $this->newImageFilename, "animation" => $this->animation), 'http://localhost/monty'
        );

        echo '{"status":"success", '
        . '"image":"' . __SITE_URL . '/' . $this->dir . '/' . $this->newImageFilename . '", '
        . '"showId":"' . $this->postData["showId"] . '",'
        . '"animation":"' . $this->animation . '",'
        . '"music":"null", '
        . '"html" : "' . urlencode($html) . '"}';
        exit;
    }

    /**
     * display the json to be read by javascript
     */
    public function sendMusicAjaxJsonResult() {
        echo '{"status":"success", '
        . '"showId":"' . $this->postData["showId"] . '",'
        . '"image":"null", '
        . '"music":"' . $this->postData["showId"] . '.mp3' . '"}';
        exit;
    }

}

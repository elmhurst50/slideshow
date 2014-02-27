<?php

use samjoyce\simpleimage\SimpleImage;

Class montageUpload {

    public function __construct() {
        $this->postData = $_POST;
        $this->montage = new MontageFromFolder($this->postData["showId"]);


// A list of permitted file extensions
        $this->allowed = array('png', 'jpg', 'gif', 'mp3');
        
        $this->saveFile();
        
        $this->sendAjaxJsonResult();
    }

//creates a thumb folder and places thumbs inside
    public function createThumb($original, $destination, $filename, $width = 200, $height = 150) {
        if (!file_exists($destination)) {
            mkdir($destination);
        }
        $image = new SimpleImage();
        $image->load($original);
        $image->resize($width, $height);
        $image->save($destination . '/' . $filename);
    }

    public function saveOriginal($original, $new, $optWidth = 1920, $optHeight = 1080) {
        $image = new SimpleImage();
        $image->load($original);
        if ($image->getWidth() > $optWidth) {//resize if image is massive
            $image->resizeToWidth($optWidth);
        }
        $image->save($new);

        return $image->getRatio();
    }

    public function saveMusic($original, $destination, $filename) {
        if (!file_exists($destination)) {
            mkdir($destination);
        }
        move_uploaded_file($original, $destination . '/' . $filename);
    }

    public function saveFile() {
        if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {

            $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

            //exit if not allowed extension
            if (!in_array(strtolower($extension), $this->allowed)) {
                echo '{"status":"error"}';
                exit;
            }

            $this->newImageFilename =  uniqid() . '.' . $extension;
            $this->dir = 'uploads/' . $this->postData["showId"];

            if ($extension == 'mp3') {//save music
                saveMusic($_FILES['upl']['tmp_name'], $this->dir . '/music', $this->postData["showId"] . '.mp3');
                echo '{"status":"success", '
                . '"showId":"' . $this->postData["showId"] . '",'
                . '"image":"null", '
                . '"music":"' . $this->postData["showId"] . '.mp3' . '"}';
                exit;
            } else {//save image
                $ratio = $this->saveOriginal($_FILES['upl']['tmp_name'], $this->dir . '/' . $this->newImageFilename);
                $this->createThumb($this->dir . '/' . $this->newImageFilename, $this->dir . '/thumbs', $this->newImageFilename);
                $this->animation = $ratio > 0.65 ? "scrollUp" : "zoom";
            }
        }
    }

    public function sendAjaxJsonResult() {
        $html = $this->montage->createEdit('~',
                $this->postData["showId"], 
                $val = array("title" => "", "text" => " ", "image" => __SITE_URL .'/'.  $this->dir . '/' . $this->newImageFilename, "animation" => $this->animation),
                'http://localhost/monty'
                );

        echo '{"status":"success", '
        . '"image":"' .  __SITE_URL .'/'.  $this->dir . '/' . $this->newImageFilename . '", '
        . '"showId":"' . $this->postData["showId"] . '",'
        . '"animation":"' . $this->animation . '",'
        . '"music":"null", '
        . '"html" : "' . urlencode($html) . '"}';
        exit;
    }

}

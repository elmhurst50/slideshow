<?php

use samjoyce\montageinterface\montageInterface;

class MontageFromFolder implements montageInterface{

    
     /**
     * 
     * @param string $showId the unique id of the required slideshow
     */
    function __construct($folder, $path = 'uploads') {
       
        $this->path = $path;
        if($folder!='new'){ 
            $this->folder = $folder;
            $this->setMontage();
        }else{
            $this->startMontage();
        }
        $this->im = class_implements($this);
        
    }

    
    public function getslide($slideNo) {
       $this->slides[$slideNo]["imageURL"] = $this->slides[$slideNo]["image"];
       return $this->slides[$slideNo];
    }
    
    public function getMusic() {
        $file = $this->path . $this->folder . '/music/' . $this->folder . '.mp3';
        return file_exists($file) ? $file : false;
      
    }
    
    public function numberOfSlides() {
        return count($this->slides);
    }
    
   
    /**
     * Set this instance from a json object
     * @param json $json
     */
    private function setFromJSON($json) {
        $jsonArray = json_decode($json, true);
        foreach ($jsonArray as $key => $value) {
            $this->$key = $value;
        }
    }

  
    /**
     * Set the instance montage data from file with json
     * @param string $showId unique id of slideshow
     * @return boolean false if no file exsits
     */
    public function setMontage() {
        $file = $this->path . '/' . $this->folder . '/slideData.txt';
        if (file_exists($file)) {
            $handle = fopen($file, "r");
            $this->setFromJSON(fread($handle, filesize($file)));
            fclose($handle);
        } else {
            return false;
        }
    }

    
      //create a slideshow directory 
    public function startMontage() {
        $this->id = uniqid();
        mkdir($this->path . '/' . $this->id);
        $file = $this->path . '/' . $this->id . '/slideData.txt';
        $handle = fopen($file, "w");
        fwrite($handle, json_encode($this));
        fclose($handle);
       }
    
     /**
     * Display all slides from selected montage
     * @global type $selectedShow
     */
    public function displayEdit() {
       $index = 0;
        foreach ($this->slides as $show => $val) {
            echo $this->createEdit($index, $this->id, $val);
            $index++;
        }
    }
    
    public function createEdit($index, $showId, $val=array("title"=>" ", "text"=>" ", "image"=>" ", "animation"=>" "), $folder = 'uploads'){
          $editPanel = '<div class="row panel list-item slideEditor">'//slideEditor is for jquery selector
            . '<div class="col-sm-2"><img src="' . $val["image"] . '"  /></div>'
            . '<div class="col-sm-10">'
            . '<div class="form-group col-sm-12">'
            . '<label for="imageTitle_' . $index . '" class="col-sm-3 control-label">Image Title</label>'
            . '<div class="col-sm-9">'
            . '<input type="text" class="form-control" name="imageTitle_' . $index . '" value="' . $val["title"] . '" placeholder="Image Title">'
            . '</div>'
            . '</div>'
            . '<div class="form-group col-sm-12">'
            . '<label for="imageText_' . $index . '" class="col-sm-3 control-label">Image Description</label>'
            . '<div class="col-sm-9">'
            . '<textarea class="form-control" name="imageText_' . $index . '"  placeholder="Image Description">' . $val["text"] . '</textarea>'
            . '</div>'
            . '</div>'
            . '<input type="hidden" name="imageId_' . $index . '" value="' . $val["image"] . '" />'
            . '<input type="hidden" name="animation_' . $index . '" value="' . $val["animation"] . '" />'
            . '<div class="col-sm-12 text-right">'
            . '<a href="#" class="btn deleteButton"><span class="glyphicon glyphicon-trash"></span> Delete</a>'
            . '<a href="#" class="btn upButton"><span class="glyphicon glyphicon-arrow-up"></span> Move Up</a>'
            . '<a href="#" class="btn downButton"><span class="glyphicon glyphicon-arrow-down"></span> Move Down</a>'
            . '</div>'
            . '</div>'
            . '</div>';
          
          return $editPanel;
    }
   
}

<?php

class montageIndexFromFolder{

    function __construct() {
        //slideshows is a list of slideshows in directory
        $this->slideshows = scandir('uploads');
    }

    function getSlideshowData($showId) {
        $file = 'uploads/' . $showId . '/slideData.txt';
        if (file_exists($file)) {
            $handle = fopen($file, "r");
            $fileContents = fread($handle, filesize($file));
            $data = json_decode($fileContents);
            //create image datafield
            $data->image = $data->slides[0]->image;
            fclose($handle);

            return $data;
        }
        return false;
    }

    /**
     ** return all slideshows
     *  return array $showdata all of the slideshow in folder
     */
    function allSlideshows() {
        $showData = array();
        //show becomes folder name
        foreach ($this->slideshows as $show) {
            if ($show != "." && $show != "..") {//dont show . and .. folders
                //if data in tact add to array
                $slideshowData = $this->getSlideshowData($show);
                $slideshowData===false ? : array_push($showData, $slideshowData);
           }
        }
        return $showData;
    }
}
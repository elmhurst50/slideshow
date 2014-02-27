<?php

class montageSave extends MontageFromFolder {

    function __construct($showId, $path = 'uploads/') {
        parent::__construct($showId);
        $this->path = $path;
        $this->id = $showId;
        define("FIELDS", 4);
        $this->showFields = array("showTitle", "showDesc", "showLocation", "showMusic", "showMusicOnOff"); //fields to collect that are not image details
        }

    /**
     * Set the main detials for whole show
     * @param array $showData all clean POST data
     * @param array $showFields fields from POST to collect, registered in constructor
     */
    function getShowInfo($showData) {
        foreach ($this->showFields as $key) {
            $this->$key = $showData[$key];
        }
    }

    /**
     * make an array stripping out show details to allow slide/image chunking
     * @param array $showData all of the clean POST data
     * @param array $showFields fields from the form to be removed from POST data to allow array chunk for image sorting
     * @param array $otherFeild ottherfields that need stripping ie submit value
     * @return array
     */
    function getImageInfo($showData, $otherFields) {
        foreach ($this->showFields as $key) {
            unset($showData[$key]);
        }
        foreach ($otherFields as $key) {
            unset($showData[$key]);
        }
        return $showData;
    }

    /**
     * Set slides array 
     * @param array $rawImageInfo
     * @param int FIELDS set in constructor
     */
    function sortImageInfo($postData) {
        $rawImageInfo = $this->getImageInfo($postData, ["saveShow", "showId"]); //information about each image
        $result = array();
        $x = 0;
        $data = array_chunk($rawImageInfo, FIELDS); //chop array into individal chuunks based on number of FIELDS

        foreach ($data as $slide) {
            if ($slide[2] != "") {//if image is set
                $result[$x]["title"] = $slide[0];
                $result[$x]["text"] = $slide[1];
                $result[$x]["image"] = $slide[2];
                $result[$x]["animation"] = $slide[3];
                $x++;
            }
        }

        $this->slides = $result;
    }

    /**
     * Save info into file with JSON
     * @param array $showInfo all data about show
     */
    function saveInfo() {
        $result = json_encode($this);
        $file = $this->path . $this->id . '/slideData.txt';
        $handle = fopen($file, "w");
        fwrite($handle, $result);
        fclose($handle);
    }

    //return the location to redirect once saved
    function getSubmit($postData) {
        return $postData["saveShow"];
    }

    /**
     * 
     * @global class $location used for getting geo coords
     * @param array $postData
     * @return string - location of redirect
     */
    function prepareForSave($postData) {
        $location = new samjoyce\location\Location();
        $this->getShowInfo($postData); //show information that will eventually store show array

        $this->sortImageInfo($postData); //turn all mages into bunch of arrays

        $this->geo = $location->getAddressCoordinates($this->showLocation); //get array of details baout geo location

        return $this->getSubmit($postData); //where to redirect
    }

    /**
     * Scan for unused files
     * @return array files to be moved
     */
    function scanFolder() {
        $files = scandir($this->path . $this->id);
        $keepImages = array();
        $results = array();
        foreach ($this->slides as $keep) {//create array of images being used
            array_push($keepImages, $keep["image"]);
        }
        foreach ($files as $check) {//check files are being used, not a folder or the data file
            if (!in_array($check, $keepImages) && !is_dir($this->path . $this->id . '/' . $check) && $check !== "slideData.txt") {
                array_push($results, $check);
            }
        }
        return $results;
    }

    /**
     * Move unused files to a soft delete folder
     */
    function cleanFolder() {
        $timestamp = date('Y-m-d');
        $path = $this->path . $this->id;
        $filesToMove = $this->scanFolder();

        if (!empty($filesToMove)) {
            if (!file_exists($this->path . $this->id . '/softDeletes')) {
                mkdir($this->path . $this->id . '/softDeletes');
            }

            foreach ($filesToMove as $file) {
                rename($path.'/'.$file, $path.'/softDeletes/' .$timestamp.'-'. $file);
            }
        }
    }

    /**
     * Organise the save
     * @param array $postData
     */
    function save($postData) {
        $save = $this->prepareForSave($postData);
        $this->saveInfo();
        //$this->cleanFolder();
       }

}

<?php

/*
 * deletes a montage from path
 */

class montageDelete {

//delete all files and folders in selected directory.
    public function eraseFolder($dir) {
        if (file_exists($dir) && $dir != "") {
            $files = glob($dir . '/*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file);
                } // delete file
            }
            rmdir($dir);
        }
    }

    /*
     * delete each folder individually
     */
    public function deleteShow($folder) {
        $this->eraseFolder($folder . '/music');
        $this->eraseFolder($folder . '/thumbs');
        $this->eraseFolder($folder);
    }

}

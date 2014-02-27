<?php

Class adminController extends baseController{
    
    /*
     * List all available montages on server
     */
    public function index(){
        $this->registry->template->heading = 'This is the blog Index';
        $this->registry->template->show('admin_index');
    }
    
    /*
     * Edit selected montage on serrver
     */
    public function edit(){
        //load montage into views variables
        $this->registry->template->montage = new MontageFromFolder($this->registry->args[0]);
        $this->registry->template->heading = 'edit screen';
        
        $this->registry->template->show('admin_edit');
    }
    
    /*
     * Ajax request for upaoding files
     */
    public function upload(){
        $upload = new montageUpload;
    }
    
    
    
    /*
     * Save the file into the directory
     */
    public function save(){
       $save = new montageSave($this->registry->args[0]);
       $save->save($_POST);
       
       header('location: '.__SITE_URL.'/admin');
      
    }
    
    /*
     * Delete a montage file
     */
    public function delete(){
        $delete = new montageDelete;
        $delete->deleteShow(__SITE_PATH. '/uploads/' . $this->registry->args[0]);
        header('location: '.__SITE_URL.'/admin');
        
    }
}
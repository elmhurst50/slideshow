<?php

use samjoyce\montageplayer\montagePlayer;

Class playerController extends baseController{
    
    public function index() {
        $this->registry->template->show('player_index');
    }
    
    public function archive(){
        //get the requested montage object
        $montage = new MontageFromFolder($this->registry->args[0]);
       
        //pass montage into the player
        $this->registry->template->player = new montagePlayer($montage);
       
        $this->registry->template->show('player_index');
    }
}

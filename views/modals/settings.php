<!-- Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

<div class="modal-header col-xs-12">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Player Settings</h4>
      </div>


      <div class="modal-body  col-xs-12">
            <div id="settings" >
            <div>
                <p class="col-sm-7"><strong>Animation</strong><br />Slower computers may struggle with image effects. Turning off will also save battery life on mobile devices.</p>
                <div id="settings-animation" class="btn-group col-sm-5">
                    <button type="button" class="btn btn-primary <?php if($player->settings["animation"]=='true'){echo 'active';}?>">On</button>
                    <button type="button" class="btn btn-primary <?php if($player->settings["animation"]=='false'){echo 'active';}?>">Off</button>
                </div>
            </div>
              <div>
                  <p class="col-sm-7"><strong>Transitions</strong><br />Slower computers may struggle with fade effects, turn off to save battery power as well</p>
                <div id="settings-transition" class="btn-group col-sm-5">
                    <button type="button" class="btn btn-primary <?php if($player->settings["transition"]=='fade'){echo 'active';}?>" value="true">On</button>
                    <button type="button" class="btn btn-primary <?php if($player->settings["transition"]=='none'){echo 'active';}?>" value="false">Off</button>
                   </div>
            </div>
            
            <div>
                <p class="col-sm-7"><strong>Captions</strong><br />Removes the information panel on each slide</p>
                <div id="settings-captions" class="btn-group col-sm-5">
                    <button type="button" class="btn btn-primary <?php if($player->settings["captions"]=='true'){echo 'active';};?>">On</button>
                    <button type="button" class="btn btn-primary <?php if($player->settings["captions"]=='false'){echo 'active';};?>">Off</button>
                </div>
            </div>
            
             <div>
                 <p class="col-sm-7"><strong>Music Volume</strong><br />Turning the volume off will save bandwidth considerably, ideal for roaming data networks.</p>
                <div id="settings-music" class="btn-group col-sm-5">
                    <button type="button" class="btn btn-primary <?php if($player->settings["music"]=='0'){echo 'active';};?>" value="0">Off</button>
                    <button type="button" class="btn btn-primary <?php if($player->settings["music"]=='0.2'){echo 'active';};?>" value="0.2">Quiet</button>
                    <button type="button" class="btn btn-primary <?php if($player->settings["music"]=='1'){echo 'active';};?>" value="1">Normal</button>
                </div>
            </div>
        </div>


   </div>
      <div class="modal-footer" style="clear:both;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-settings">Save Settings</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

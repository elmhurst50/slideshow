<!-- Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

<div class="modal-header col-xs-12">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Contact Us</h4>
      </div>


      <div class="modal-body  col-xs-12">
           <form class="form-horizontal col-sm-12" id="contactForm" method="post" action="">
                <div class="row col-xs-12">
                    <div class="form-group col-md-12">
                                <label for="contactName" class="col-sm-3 control-label">Your Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="contactName" placeholder="Your name">
                               </div>
                    </div>
                      <div class="form-group col-md-12">
                                <label for="contactEmail" class="col-sm-3 control-label">Your Email</label>
                                <div class="col-md-9">
                                          <input type="email" class="form-control" name="contactEmail" placeholder="Your email address">
                               </div>
                    </div>
                    
                     <div class="form-group col-md-12">
                                <label for="contactMessage" class="col-sm-3 control-label">Message</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="contactMessage"></textarea>
                               </div>
                               
                    </div>
                    
                    
                </div>
               
               
           </form>
      </div>

   
      <div class="modal-footer" style="clear:both;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="snd-message">Send Message</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


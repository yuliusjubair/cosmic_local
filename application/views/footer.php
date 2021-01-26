<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Download File</h5>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <button onclick="loader()" class="btn btn-primary" type="button" id="ExportExcel"><i class="fa fa-file-text-o"></i>&nbsp;Format Excel</button>

                    <button onclick="loader()" class="btn btn-primary" type="button" id="ExportCsv"><i class="fa fa-file-text-o"></i>&nbsp;Format CSV</button>

                     <div id="progress-bar" style="display:none;margin-top: 11px;">
	                    <img src="<?php echo base_url(); ?>assets/images/busy.gif"/>&nbsp;Waiting For Download File...
	                </div>

                </div>

                

            </div>
           
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
 </section>
	</div>

            <!-- <div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Cosmic</span>
							Application &copy; <?php echo Date('Y'); ?>
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
					</div>
				</div>
			</div> -->
			      <footer class="main-footer">
			        <div class="footer-left">
			          Copyright &copy; 2020 <div class="bullet"></div> Present By <a href="https://nauval.in/">Cosmic</a>
			        </div>
			        <!-- <div class="footer-right">
			          2.3.0
			        </div> -->
			      </footer>
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->
	</div>
	<!-- General JS Scripts -->
      

	</body>
</html>

<script type="text/javascript">

	function loader(){
		$("#progress-bar").show();
		/*setTimeout(function() {
	      $("#progress-bar").hide();
	   }, 6000);*/
	}

	function loader_rangkuman(){
		$("#progress-bar").show();
		setTimeout(function() {
	      $("#progress-bar").hide();
	   }, 6000);
	}
</script>
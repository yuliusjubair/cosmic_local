            </div><!-- /.row -->
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Download File</h5>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <button class="btn btn-primary" type="button" id="ExportExcel"><i class='ace-icon fa fa-file bigger-120'></i>Format Excel</button>

                    <button class="btn btn-primary" type="button" id="ExportCsv"><i class='ace-icon fa fa-file bigger-120'></i>Format CSV</button>
                </div>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

            <div class="footer" >
            	<div class="footer-inner" >
            		<!-- #section:basics/footer -->
            		<div class="footer-content">
            			<span class="bigger-120">
            				<span class="blue bolder">COSMIC</span>
            				&copy; <?php echo Date('Y'); ?>
            			</span>
            		</div>
            		<!-- /section:basics/footer -->
            	</div>
            </div>
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->
		<!-- basic scripts -->
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/template/ace/js/jquery.js'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->
		<!--[if IE]>
        <script type="text/javascript">
         window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/template/ace/js/jquery1x.js'>"+"<"+"/script>");
        </script>
        <![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>assets/template/ace/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap-tag.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/jquery.hotkeys.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap-wysiwyg.js"></script>

		<!--[if lte IE 8]>
		  <script src="<?php echo base_url(); ?>assets/template/ace/js/excanvas.js"></script>
		<![endif]-->
		<script src="<?php echo base_url(); ?>assets/template/ace/js/jquery-ui.custom.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/jquery.ui.touch-punch.js"></script>
		
		<!-- ace scripts -->
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.scroller.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.colorpicker.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.fileinput.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.typeahead.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.wysiwyg.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.spinner.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.treeview.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.wizard.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.aside.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.ajax-content.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.touch-drag.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.sidebar.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.submenu-hover.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.widget-box.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.settings.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.settings-rtl.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.settings-skin.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.searchbox-autocomplete.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/datatables/js/jquery.dataTables.min.js"></script>
		
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/bootstrap-tag.js"></script>
	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jquery-3.5.1.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.print.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/main.js"></script>
	</body>
</html>
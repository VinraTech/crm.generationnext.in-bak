<div class="page-footer">
    <div class="page-footer-inner">
        <?php echo date('Y');?> &copy; Express Paisa
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- View Employee Details Modal Starts-->
<div class="modal fade" id="ViewEmployeeDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLabel">View Employee Details</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped" id="appendemployeedata">
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View User Details Modal Ends-->
<!-- View Lead Details Modal Starts-->
<div class="modal fade" id="LeadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" id="LeadModalTitle"></h3>
            </div>
            <div class="modal-body">
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#LeadDetailTab" aria-controls="LeadDetailTab" role="tab" data-toggle="tab" aria-expanded="true">Lead Details</a>

                        </li>
                        <li role="presentation" class=""><a href="#AllocationDetailTab" aria-controls="AllocationDetailTab" role="tab" data-toggle="tab" aria-expanded="false">Lead Allocation Details</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="LeadDetailTab">
                            <table class="table table-bordered table-striped" id="AppendLeadData">
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="AllocationDetailTab">
                            <table class="table table-bordered table-striped" id="AppendAllocateLeadData">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Lead Details Modal Ends-->
<script>
jQuery(document).ready(function() {    
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo features 
    Tasks.initDashboardWidget(); // init tash dashboard widget  
});
</script>
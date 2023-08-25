@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php  use App\Employee;?>
<div class="page-content-wrapper">
	<div class="page-content">
		<div class="page-head">
			<div class="page-title">
				<h1>Dashboard</h1>
			</div>
		</div>
		@if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true"></span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
		<div class="row margin-top-10">
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="dashboard-stat2">
					<div class="display">
						<div class="number">
							<h3 class="font-purple-soft">MIS</h3>
						</div>
					</div>
					<div class="progress-info">
	                    <div class="status">
	                        <div class="status-title">
	                        	<?php $access = Employee::checkAccess();
            						if($access =="false"){ 
            							$year = date('Y').'-'.(date('Y')+1);
            							$yearmonth = date('n').'-'.date('Y');
            							?>
	                            		<a href="{{url('s/admin/file-report-results?type=Team Wise&team='.Session::get('empSession')['id']).'&y='.$year.'&ym='.$yearmonth}}">View More Details</a>
	                            	<?php }else{?>
										<a href="{{url('s/admin/file-reports')}}">View More Details</a>
	                            	<?php }  ?>
	                        </div>
	                    </div>
	                </div>
				</div>
			</div>
			@foreach($getModules as $key => $module)
				@if(Session::get('empSession')['type']=="admin" && $module['name'] == "Received Leads")
					<?php $module['name'] = "Leads History"; ?>
				@endif

				@if($module['name'] != 'Allocated Leads' && $module['name'] != 'Closed Leads' && $module['name'] != 'UnAllocated Leads' && $module['name'] != 'Generated Leads' && $module['name'] != 'Operation Files' )
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat2">
							<div class="display">
								<div class="number">
									<h3 class="font-purple-soft">{!!$module['table_count']!!}</h3>
									<small>{{$module['name']}}</small>
								</div>
								<div class="icon">
									<i class="{{$module['icon']}}"></i>
								</div>
							</div>
							<div class="progress-info">
			                    <div class="status">
			                        <div class="status-title">
			                            <a href="{{url($module['view_route'])}}">View More Details</a>
			                        </div>
			                    </div>
			                </div>
						</div>
					</div>
				@endif
			@endforeach


			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="dashboard-stat2">
							<div class="display">
								<div class="number">
									<?php
									if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['is_access']=="full"){
										$approved_file = $file_disb = DB::table('file_disbursements')->where('chk','0')->where('disb_type','disbursed')->count();
									}else{
										$approved_file = $approved_file_count;
									}
									?>
									<h3 class="font-purple-soft"><?php echo $approved_file; ?></h3>
									<small>Approved File</small>
								</div>
								<div class="icon">
									<i class="{{$module['icon']}}"></i>
								</div>
							</div>
							<div class="progress-info">
			                    <div class="status">
			                        <div class="status-title">
			                            <a href="{{url('s/admin/disbursement-files')}}">View More Details</a>
			                        </div>
			                    </div>
			                </div>
						</div>
					</div>



		</div>
	</div>
</div>
@endsection
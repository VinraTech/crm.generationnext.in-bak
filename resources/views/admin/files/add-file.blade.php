@extends('layouts.adminLayout.backendLayout')
@section('content')
<?php use App\FileDropdown; use App\Employee; use App\File; 
if(Session::get('empSession')['type'] == "admin" || Session::get('empSession')['is_access']=="full"){
	$clients = DB::table('clients')->where('status',1)->get();
	$clients = json_decode(json_encode($clients),true);
}else{
	$arr_id = Employee::empsiddata();
    $ch_id = array();
    $chdata = DB::table('channel_partners')->whereIn('emp_id',$arr_id)->get();
    $chdata = json_decode(json_encode($chdata),true);
    foreach($chdata as $chp){
    	array_push($ch_id, $chp['id']);
    }
    
	//$clients = DB::table('clients')->whereIn('tel_name',$arr_id)->orWhereIn('created_emp',$arr_id)->where('status',1)->get();
   // $clients = json_decode(json_encode($clients),true);
    // dd($clients);
	$clients = $getclientdata;
}
?>
<div class="page-content-wrapper">
<div class="page-content">
<div class="page-head">
<div class="page-title">
    <h1>File's Management </h1>
</div>
</div>
<ul class="page-breadcrumb breadcrumb">
<li>
    <a href="{!! action('AdminController@dashboard') !!}">Dashboard</a>
    <i class="fa fa-circle"></i>
</li>
<li>
    <a href="{{ action('FileController@files') }}">Files</a>
</li>
</ul>
<div class="row">
<div class="col-md-12 ">
    <div class="portlet blue-hoki box ">
	    <div class="portlet-title">
	        <div class="caption">
	            <i class="fa fa-gift"></i>{{$title}}
	        </div>
	    </div>

	    <div class="portlet-body form">
	        <form  class="form-horizontal" method="get" action="{{url('/s/admin/add-file')}}">@csrf
	        	<div class="form-body">
	                <div class="row">
            			<div class="form-group">
                            <label class="col-md-3 control-label">Select Client:</label>
                            <div class="col-md-4">
                                <select name="client_id" class="selectpicker form-control getfileNo" required data-live-search="true" data-size="7" data-width="100%"> 

                                    <option value="">Select</option>
                                    
                                    @foreach($clients as $client)
										<option value="{{$client['id']}}" @if(isset($_GET['client_id']) && $_GET['client_id']==$client['id'])  selected @endif>{{$client['customer_name']}} ({{$client['company_name']}}-{{$client['mobile']}})</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                       	</div>
                       	@if(isset($_GET['client_id']) && !empty($_GET['client_id']))
	                       	<div class="form-group">
	                            <label class="col-md-3 control-label">View Details:</label>
	                            <div class="col-md-4">
	                                <a target="_blank" data-toggle="modal" data-target="#ClientDetailModal" class="btn btn-sm green" style="margin-top: 7px;" href="javascript:;">View Details</a>
	                            </div>
	                       	</div>
	                    @endif
	                </div>
	            </div>
	            <div class="form-actions right1 text-center">
	                <button class="btn green" type="submit">Go</button>
	            </div>
	        </form>
	    </div>
	</div>
	@if($clientdetail)	
		<div class="portlet-body">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="portlet blue-hoki box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i>Files of {{$clientdetail->customer_name}}
                            </div>
                            <a target="_blank" class="btn btn-sm grey pull-right" style="margin-top: 7px;" href="{{url('/s/admin/generate-file/'.$clientdetail->id)}}">Generate new File for {{$clientdetail->customer_name}}</a>
                        </div>
                        <div class="portlet-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                File No
                                            </th>
                                            <th>
                                                Department
                                            </th>
                                            <th>
                                                Facility Type
                                            </th>
                                            <th>
                                                File Creation Date
                                            </th>
                                            <th>
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @if(!empty($clientfiles))
                                        @foreach($clientfiles as $file)
                                            <tr>
                                                <td><a target="_blank" title="View Details" class="btn btn-sm blue" href={{url('/s/admin/create-applicants/'.$file['id'].'?open=modal')}}>{{$file['file_no']}}</a></td>
                                                <td>
                                                    {{$file['department']}}
                                                </td>
                                                <td>
                                                    {{$file['facility_type']}}
                                                </td>
                                                <td>
                                                {{date('d M Y',strtotime($file['created_at']))}}</td>
                                                <td><a target="_blank" title="View Details" class="btn btn-sm green" href="{{url('/s/admin/generate-file/'.$file['client_id'].'?fileid='.$file['id'])}}">Copy</a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" style="text-align: center">
                                            No Files found.
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
</div>
</div>
@if(isset($_GET['client_id']) && !empty($_GET['client_id']))
<div class="modal fade" id="ClientDetailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">View Client Details</h5>
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-striped text-center">
			<tbody>
				<tr>
					<td>
						Client Id
					</td>
					<td>
						{{$clientdetail->client_id}}
					</td>
				</tr>
				<tr>
					<td>
						Company Name
					</td>
					<td>
						{{$clientdetail->company_name}}
					</td>
				</tr>
				<tr>
					<td>
						Applicant Name
					</td>
					<td>
						{{$clientdetail->name}}
					</td>
				</tr>
				<tr>
					<td>
						D.O.B
					</td>
					<td>
						{{$clientdetail->dob}}
					</td>
				</tr>
				<tr>
					<td>
						Co-Applicant Name
					</td>
					<td>
						{{$clientdetail->co_applicant_name}}
					</td>
				</tr>
				<tr>
					<td>
						Co-Applicant D.O.B
					</td>
					<td>
						{{$clientdetail->co_applicant_dob}}
					</td>
				</tr>
				<tr>
					<td>
						Email
					</td>
					<td>
						{{$clientdetail->email}}
					</td>
				</tr>
				<tr>
					<td>
						Mobile
					</td>
					<td>
						{{$clientdetail->mobile}}
					</td>
				</tr>
				<tr>
					<td>
						PAN
					</td>
					<td>
						{{$clientdetail->pan}}
					</td>
				</tr>
				<tr>
					<td>
						Sale Officer
					</td>
					<td>
						<?php $saleofficer = Employee::getemployee($clientdetail->sale_officer) ?>
						{{$saleofficer['name']}}
					</td>
				</tr>
			</tbody>
		</table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>
@endif
@stop
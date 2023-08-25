@extends('layouts.adminLayout.backendLayout')

@section('content')

<?php use App\FileDropdown; use App\Employee; use App\File;?>

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

				        <form  class="form-horizontal" method="get" action="{{url('/s/admin/search-files')}}">@csrf

				        	<div class="form-body">

				                <div class="row">

                        			<div class="form-group">

			                            <label class="col-md-3 control-label">Select Search Type:</label>

			                            <div class="col-md-4">

			                                <select name="search_by" class=" form-control"> 

			                                    <?php $searchByAarr = array('company'=>'Company Name','applicant'=>'Applicant Name','file_no'=>'File Number'); ?>

			                                    @foreach($searchByAarr as $skey=> $search)

			                                    	<option value="{{$skey}}" @if(isset($_GET['search_by'])  && $_GET['search_by']==$skey) selected @endif>{{$search}}</option>

			                                    @endforeach

			                                </select>

			                            </div>

			                       	</div>

			                       	<div class="form-group">

	                                    <label class="col-md-3 control-label">Enter Search Query:</label>

	                                    <div class="col-md-4">

	                                        <input type="text" placeholder="Enter Search Query" name="search" style="color:gray" autocomplete="off" class="form-control" @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif/>

	                                    </div>

	                                </div>

				                </div>

				            </div>

				            <div class="form-actions right1 text-center">

				                <button class="btn green" type="submit">Submit</button>

				            </div>

				        </form>

				    </div>

				</div>

				@if($files)	

					<div class="portlet-body">

	                    <div class="row">

	                        <div class="col-md-12 col-sm-12">

	                            <div class="portlet blue-hoki box">

	                                <div class="portlet-title">

	                                    <div class="caption">

	                                        <i class="fa fa-cogs"></i>Searched Files

	                                    </div>

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

	                                                        Applicant Name

	                                                    </th>

	                                                    <th>

	                                                        Company Name

	                                                    </th>

	                                                    <th>

	                                                        Facility Type

	                                                    </th>

	                                                    <th>

	                                                        Loan Amt.

	                                                    </th>

	                                                    <th>

	                                                       Bank

	                                                    </th>

	                                                    <th>

	                                                        Login Date

	                                                    </th>

	                                                    <!-- <th>

	                                                        Sales Officer

	                                                    </th> -->

	                                                     <th>

	                                                        File Status

	                                                    </th>

	                                                    <th>

	                                                        Actions

	                                                    </th>

	                                                </tr>

	                                            </thead>

	                                            <tbody>

                                                @foreach($files as $file)

                                                	<?php 
                                                	// $salesofficer = Employee::getemployee($file['salesofficer']); 
                                                	?>

                                                    <tr>

                                                        <td><a target="_blank" title="View Details" class="btn btn-sm blue" href={{url('/s/admin/create-applicants/'.$file['id'].'?open=modal')}}>{{$file['file_no']}}</a></td>

                                                        <td>

                                                            {{$file['department']}}

                                                        </td>

                                                        <td>

                                                            {{$file['client_name']}}

                                                        </td>

                                                        <td>

                                                            {{$file['company_name']}}

                                                        </td>

                                                        <td>

                                                            {{$file['loan_type']}}

                                                        </td>

                                                        <td>

                                                            <?php 
                                                            // echo File::getLoanAmt($file);
                                                            ?>
                                                            {{$file['loan_amount']}}

                                                        </td>

                                                        <td>

                                                        	@if(!empty($file['getbank']))

                                                        		{{$file['getbank']['bankdetail']['short_name']}}

                                                        	@else

                                                        		Not Moved yet

                                                        	@endif

                                                        </td>

                                                        <td>

                                                        {{date('d M Y h:ia',strtotime($file['created_at']))}}</td>

                                                        

                                                        <td>{{ucwords($file['move_to'])}}</td>

                                                        <td><a data-fileid="{{$file['id']}}" title="View Summary" class="btn btn-sm green getSummary" href="javascript:;">View Summary</a>

                                                        @if($file['move_to'] == "partially" || $file['move_to'] == "disbursement")

                                                        	<a target="_blank" title="Update Disbursement Details" class="btn btn-sm blue margin-top-10" href="{{url('/s/admin/update-disbursement-details/'.$file['id'])}}">Update Disbursement</a>

                                                        @endif

                                                        @if($file['move_to'] == "partially")

                                                        	<a title="Export History" class="btn btn-sm blue margin-top-10" href="{{ url('s/admin/export-partially-files/'.$file['id'])}}">Export History</a>

                                                        @endif

                                                        @if($file['move_to'] == "disbursement")

                                                        	<a title="Export History" class="btn btn-sm blue margin-top-10" href="{{ url('/s/admin/export-file-history/'.$file['id'])}}">Export History</a>

                                                        @endif

                                                    	</td>

                                                    </tr>

                                                @endforeach

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

<!-- summary Details -->

<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                <span aria-hidden="true">&times;</span>

                </button>

                <h5 class="modal-title" id="exampleModalLabel">File Summary Details</h5>

            </div>

            <div class="modal-body">

                <table class="table table-bordered table-striped text-center">

	                <tbody id="AppendsummaryData">

	                	

	                </tbody>

                </table>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div>

<!-- summary Details -->

<script type="text/javascript">

	$(document).ready(function(){

		$(document).on('click','.getSummary',function(){

			$('.loadingDiv').show();

			var fileid = $(this).data('fileid');

			$.ajax({

				url : '/s/admin/get-file-summary',

				data : {fileid:fileid},

				type : 'post',

				success:function(resp){

					$('#AppendsummaryData').html(resp);

					$('#summaryModal').modal('show');

					$('.loadingDiv').hide();

				},

				error:function(){

					alert('error');

				}

			})

		})

	})

</script>

@stop
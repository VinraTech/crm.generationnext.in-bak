<?php use App\Employee; ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
	.table-bordered {
      border: 1px solid #dee2e6;
    }
	.table {
		
		margin-left:10%!important;
		margin-right:10%!important;
		margin-bottom: 1rem;
		background-color: transparent;
	}
	table {
		border-collapse: collapse;
	}
	thead {
		display: table-header-group;
		vertical-align: middle;
		border-color: inherit;
	}
	tr {
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}	
	.table-bordered td, .table-bordered th {
		border: 1px solid #dee2e6;
	}
	.table td, .table th {
		padding: 0.75rem;
		vertical-align: top;
		border-top: 1px solid #dee2e6;
		text-align: left!important;
	}
	.text-center {
		text-align: center!important;
	}
	.h3, h3 {
		font-size: 1.75rem;
	}
	.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
		margin-bottom: 0.5rem;
		font-family: inherit;
		font-weight: 500;
		line-height: 1.2;
		color: inherit;
	}
	b, strong {
		font-weight: bolder!important;
	}
    .table td {
		font-size:12px;
	}
	.root{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: #35aa47;
		margin-right:10px;
	}
	.team_id{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: #607d8b;
		margin-right:10px;
	}
	.level_1{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: Purple;
		margin-right:10px;
	}
	.level_2{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: gray;
		margin-right:10px;
	}
	.level_3{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: orange;
		margin-right:10px;
	}
	
	.level_4{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: #35aa47;
		margin-right:10px;
	}
	.level_5{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: #35aa47;
		margin-right:10px;
	}
	.emp-info{
		padding:10px;
		font-size:12px;
		font-weight:bold;
		color: #FFFFFF;
		background-color: #35aa47;
		margin-right:10px;
		cursor:pointer;
	}
	
	.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
	
</style>

</head>
<body>
    
           
         
    
     <h3 class="text-center"> <b>CRM - EMPLOYEE LIST</b></h3>
        <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-left" style="width:1%!important"><b>NO</b></th>
				<th class="text-left" style="width:1%!important"><b>ID</b></th>
                <th class="text-left" style="width:20%!important"><b>TEAMID / LEVEL / NAME</b></th>
                <th class="text-left" style="width:2%!important"><b>INFO</b></th>
                
			  </tr>
			  
            </thead>
            <tbody>
			<?php $no =0; ?>
			@foreach($getTeamLevels as $key => $level)
                    <?php $getEmpType = DB::table('employee_types')->where('short_name',$level['type'])->first(); 
                    
                    ?>
                
                    <tr>
						<td class="text-left"><?php echo $no = $no+1; ?></td>
						<td class="text-left">{{$level['id']}}</td>
						<td class="text-left"><span class="team_id">{{$level['team_id']}} </span> <span class="root">ROOT </span><span class="root"> {{$level['name']}} - {{$getEmpType->full_name}}</span></td>
					    <td class="text-left" onclick="view_emp_details()"><span class="emp-info">View</span></td>
					</tr>
					@foreach($level['getemps'] as $skey => $sublevel1)
                        <?php 
						$getEmpType = DB::table('employee_types')->where('short_name',$sublevel1['type'])->first();
                          
						?>
                     
                        <tr>
								<td class="text-left"><?php echo $no = $no+1; ?></td>
								<td class="text-left">{{$sublevel1['id']}}</td>
								<td class="text-left"><span class="team_id">{{$sublevel1['team_id']}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &nbsp;<span class="level_1">level 1</span><span class="level_1">{{$sublevel1['name']}} - @if(isset($getEmpType->full_name)){{$getEmpType->full_name}}@endif</span></td>
						        <td class="text-left" onclick="view_emp_details()"><span class="emp-info">View</span></td>
						</tr>
						@foreach($sublevel1['getemps'] as $sskey=> $sublevel2)
                            <?php 
							$getEmpType = DB::table('employee_types')->where('short_name',$sublevel2['type'])->first(); 
							
							?>
                            	  <tr style="border:none!important">
										<td class="text-left"><?php echo $no = $no+1; ?></td>
										<td class="text-left">{{$sublevel2['id']}}</td>
										<td class="text-left"><span class="team_id">{{$sublevel2['team_id']}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &raquo; &nbsp;<span class="level_2">level 2</span><span class="level_2">{{$sublevel2['name']}} - {{$getEmpType->full_name}}</span></td>
										<td class="text-left" onclick="view_emp_details()"><span class="emp-info">View</span></td>
									</tr>
                            <?php
                            $getdetails = Employee::with(['getemps'=>function($query){

                                $query->with('getemps');

                            }])->where('id',$sublevel2['id'])->first();

                            $getdetails = json_decode(json_encode($getdetails),true);
                            ?>
                            @foreach($getdetails['getemps'] as $ssskey=> $sublevel3)
                                <?php 
								$getEmpType = DB::table('employee_types')->where('short_name',$sublevel3['type'])->first();
                              
								?>
                                 
								   <tr>
										<td class="text-left"><?php echo $no = $no+1; ?></td>
										<td class="text-left">{{$sublevel3['id']}}</td>
										<td class="text-left"><span class="team_id">{{$sublevel3['team_id']}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &raquo; &raquo; &nbsp;<span class="level_3">level 3</span><span class="level_3">{{$sublevel3['name']}} - {{$getEmpType->full_name}}</span></td>
									    <td class="text-left" onclick="view_emp_details()"><span class="emp-info">View</span></td>
									</tr>



                                <?php
                                 $getdetails = Employee::with(['getemps'=>function($query){

                                     $query->with('getemps');

                                 }])->where('id',$sublevel3['id'])->first();

                                 $getdetails = json_decode(json_encode($getdetails),true);
                                ?>
                                @foreach($getdetails['getemps'] as $sssskey=> $sublevel4)
                                    <?php  $getEmpType = DB::table('employee_types')->where('short_name',$sublevel4['type'])->first(); 
									
									?>
                                   
									<tr>
										<td class="text-left"><?php echo $no = $no+1; ?></td>
										<td class="text-left">{{$sublevel4['id']}}</td>
										<td class="text-left"><span class="team_id">{{$sublevel4['team_id']}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo; &raquo; &raquo; &raquo; &nbsp;<span class="level_4">level 4</span><span class="level_4">{{$sublevel4['name']}} - {{$getEmpType->full_name}}</span></td>
									    <td class="text-left" onclick="view_emp_details()"><span class="emp-info">View</span></td>
									</tr>
								@endforeach  





                            @endforeach


                        @endforeach
                    @endforeach
                @endforeach
			</tbody>
        
		</table>
		<!-- Button to Open the Modal -->




   
        </div>
      </div>
    </div>
  </div>
</body>
</html>


<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="model_close()">&times;</span>
	<h4 class="text-left" style="margin-left: 107px;"><strong>EMPLOYEE NAME : TEST USER</strong></h4>
    <table class="table table-bordered" style="font-size:15px;font-weight:bold!important">
            <thead>
              <tr>
                <th class="text-left"><b>EMPLOYEES</b></th>
				<th class="text-left" ><b>CLIENTS</b></th>
                <th class="text-left" ><b>CHANNEL PARTNER</b></th>
                <th class="text-left" ><b>WIP FILES</b></th>
                <th class="text-left" ><b>LOGIN/BANK FILES</b></th>
                <th class="text-left" ><b>LOGIN/BANK FILES</b></th>
                <th class="text-left" ><b>APPROVED FILES</b></th>
                <th class="text-left" ><b>DECLINED FILES</b></th>
                
			  </tr>
			  
            </thead>
            <tbody>
			<tr>
                <td style="text-align:center!important;"><b>0</b></td>
				<td style="text-align:center!important;" ><b>0</b></td>
                <td style="text-align:center!important;" ><b>0</b></td>
                <td style="text-align:center!important;" ><b>0</b></td>
                <td style="text-align:center!important;" ><b>0</b></td>
                <td style="text-align:center!important;" ><b>0</b></td>
                <td style="text-align:center!important;" ><b>0</b></td>
                <td style="text-align:center!important;" ><b>0</b></td>
                
			  </tr>
            </tbody>
            <tbody>
    </table>
  </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
     function view_emp_details(){
		   var modal = document.getElementById("myModal");
	       modal.style.display = "block";
		/* $.ajax({
			   url: "s/admin/get-emp-info", 
			   type: "POST",
			   dataType: "json",
			   contentType: "application/json; charset=utf-8",
			   data: JSON.stringify({ name: 'value1', email: 'value2' }),
			   success: function (result) {
				   // when call is sucessfull
				},
				error: function (err) {
				// check the err for error details
				}
			});  */
	 
	 }
	function model_close(){
		var modal = document.getElementById("myModal");
		modal.style.display = "none";
	}
</script>
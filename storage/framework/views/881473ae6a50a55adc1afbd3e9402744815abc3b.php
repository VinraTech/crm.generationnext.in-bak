<?php $__env->startSection('content'); ?>
<?php use App\FileDropdown; use App\Employee; use App\File; use App\FileLoanDetail; use App\Bank; use App\FileDisbursement; use App\PartialFile; ?>

<?php if(isset($_GET['type'])): ?>
<?php  $requestdata = $_GET; 
   
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
                <a href="<?php echo action('AdminController@dashboard'); ?>">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
        </ul>
        <div class="row">
				<div class="col-md-12">
          <div>
            <form action="<?php echo e(url('/s/admin/export-report')); ?>">

            <?php
            if($requestdata['casestat'] == 'all')
            {
                $cast_stat_data = 'approved,declined,disbursement,login,bank,operations,partially';
            } else {
                $cast_stat_data = $requestdata['casestat'];
            }
            ?>
              
              <?php if($requestdata['type'] == "Individual"): ?>
               <input type="hidden" name="type" value="<?php echo e($requestdata['type']); ?>">
               <input type="hidden" name="bank" value="<?php echo e($requestdata['bank']); ?>">
               <input type="hidden" name="individual" value="<?php echo e($requestdata['individual']); ?>">
               <input type="hidden" name="srt" value="<?php echo e($requestdata['srt']); ?>">
               <input type="hidden" name="endt" value="<?php echo e($requestdata['endt']); ?>">
               <input type="hidden" name="prod" value="<?php echo e($requestdata['prod']); ?>">
               <input type="hidden" name="casestat" value="<?php echo e($cast_stat_data); ?>">
               <?php elseif($requestdata['type'] == "Team Wise"): ?>
                <input type="hidden" name="type" value="<?php echo e($requestdata['type']); ?>">
                <input type="hidden" name="bank" value="<?php echo e($requestdata['bank']); ?>">
                <input type="hidden" name="team" value="<?php echo e($requestdata['team']); ?>">
                <input type="hidden" name="srt" value="<?php echo e($requestdata['srt']); ?>">
                <input type="hidden" name="endt" value="<?php echo e($requestdata['endt']); ?>">
                <input type="hidden" name="prod" value="<?php echo e($requestdata['prod']); ?>">
                <input type="hidden" name="casestat" value="<?php echo e($cast_stat_data); ?>">
                <?php elseif($requestdata['type'] == "All Branches"): ?>
                 <input type="hidden" name="type" value="<?php echo e($requestdata['type']); ?>">
                 <input type="hidden" name="bank" value="<?php echo e($requestdata['bank']); ?>">
                 <input type="hidden" name="srt" value="<?php echo e($requestdata['srt']); ?>">
                 <input type="hidden" name="endt" value="<?php echo e($requestdata['endt']); ?>">
                 <input type="hidden" name="prod" value="<?php echo e($requestdata['prod']); ?>">
                <input type="hidden" name="casestat" value="<?php echo e($cast_stat_data); ?>">
                <?php endif; ?>
          <div class="portlet-title">
            <div class="actions">
              <div class="btn-group">
                <button class="btn btn-primary">Export Report</button>
                </div>
            </div>
          </div>
        </form>
        </div>
					<!-- BEGIN SAMPLE TABLE PORTLET-->
					<div class="portlet box">
						<div class="text-center" style="float: left; width: 100%; display: inline-block; position: relative;">				
							<div class="portlet-body" style="float: none; width: 100%; max-width: 400px; display: inline-block; position: relative;">					
								<div class="table-scrollable">
									<table class="table table-bordered table-striped table-hover">
									<caption style="background-color: #acacac; color: #fff; text-align: center;">Searh Query</caption>
									
									<tbody>
									<tr>
										<td>
											Type
										</td>
										<td>
											<?php echo e($requestdata['type']); ?>

										</td>
									</tr>
									<tr>
										<td>
											<?php echo e($requestdata['type']); ?>

										</td>
										<td>
											<?php if($requestdata['type'] =="Team Wise"): ?>
												<?php $emp = Employee::getemployee($requestdata['team']);
												echo $emp['name'];
												?>
											<?php elseif($requestdata['type'] =="Individual"): ?>
												<?php $emp = Employee::getemployee($requestdata['individual']);
												echo $emp['name'];
												?>
											<?php elseif($requestdata['type'] =="All Branches"): ?>
												<?php 
                                                  $banks_name = array();
                                                  if($requestdata['bank'] != 'all_banks')
                                                  {
                                                    $banks_id = explode(',', $requestdata['bank']);
                                                    foreach($banks_id as $bankid){
                                                       $bank = Bank::bankinfo($bankid);
                                                       array_push($banks_name, $bank);
                                                    }
                                                    $bank_name = implode(',',$banks_name);
                                                  } else {
                                                    $bank_name = "All Branches";
                                                  }
												echo $bank_name;
												?>
											<?php endif; ?>
										</td>
									</tr>
                                    <?php if($requestdata['type'] =="Team Wise" || $requestdata['type'] =="Individual"): ?>
                                    <tr>
                                        <td>
                                            Branch
                                        </td>
                                        <td>
                                            <?php if($requestdata['type'] =="Team Wise"): ?>
                                                <?php 
                                                  $banks_name = array();
                                                  if($requestdata['bank'] != 'all_banks')
                                                  {
                                                    $banks_id = explode(',', $requestdata['bank']);
                                                    foreach($banks_id as $bankid){
                                                       $bank = Bank::bankinfo($bankid);
                                                       array_push($banks_name, $bank);
                                                    }
                                                    $bank_name = implode(',',$banks_name);
                                                  } else {
                                                    $bank_name = "All Branches";
                                                  }
                                                echo $bank_name;
                                                ?>
                                            <?php elseif($requestdata['type'] =="Individual"): ?>
                                                <?php 
                                                  $banks_name = array();
                                                  if($requestdata['bank'] != 'all_banks')
                                                  {
                                                    $banks_id = explode(',', $requestdata['bank']);
                                                    foreach($banks_id as $bankid){
                                                       $bank = Bank::bankinfo($bankid);
                                                       array_push($banks_name, $bank);
                                                    }
                                                    $bank_name = implode(',',$banks_name);
                                                  } else {
                                                    $bank_name = "All Branches";
                                                  }
                                                echo $bank_name;
                                                ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
									<tr>
										<td>
											Start Date
										</td>
										<td>
											<?php echo e($requestdata['srt']); ?>

										</td>
									</tr>
									<tr>
										<td>
											End Date
										</td>
										<td>
											<?php echo e($requestdata['endt']); ?>

											
										</td>
									</tr>
									</tbody>
									</table>
								</div>
							</div>

						</div>
					</div>
					<!-- END SAMPLE TABLE PORTLET-->
				</div>
			</div>
        <div class="row">
        	<?php 
          //$ftssystem = array('login'=>'Work In Progress Files','operations'=>'Operation Files','bank'=>'Login/Bank Files','declined'=>'Declined Files','approved'=>'Approved Files','partially'=>'Partially Disburse File','disbursement'=>'Disbursement Files'); 

          $ftssystem = array('operations'=>'Pending Approval','login'=>'WIP Files','bank'=>'Login Bank Files','declined'=>'Declined Files','approved'=>'Approved Files','partially'=>'Partially Disburse File','disbursement'=>'Disbursement Files'); 
          ?>

        	
        <?php
          //$statusArr = array('approved'=>'Approval Process','bank'=>'Login/Bank Files','declined'=>'Declined Files','disbursement'=>'Full Disburse Files','login'=>'Work In Progress Files','operations'=>'Pending Approval Files','partially'=>'Partially Disburse File');

          $statusArr = array('operations'=>'Pending Approval','login'=>'WIP Files','bank'=>'Login Bank Files','approved'=>'Approval Process','declined'=>'Declined Files','disbursement'=>'Full Disburse Files','partially'=>'Partially Disburse File');

          $case_sta = explode(',', $cast_stat_data);
          
          
            
         
          
        ?>
		    <?php $__currentLoopData = $case_sta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		      
              
			    <div class="col-md-12">
			        <!-- BEGIN BORDERED TABLE PORTLET-->
			        <div class="portlet box green">
			            <div class="portlet-title">
			                <div class="caption">
			                	<?php echo e($statusArr[$case]); ?> 
			                </div>
			            </div>
			            <div class="portlet-body">
			                <div class="table-scrollable">
			                    <table class="table table-bordered table-hover">
			                        <thead>
			                            <tr>
			                            	<?php $deparments = array('Files'); ?>
			                            	<?php $__currentLoopData = $deparments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			                            	<th>
			                                    <?php echo e($dept); ?>

			                                </th>
			                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			                            </tr>
			                        </thead>
			                        <tbody>
			                            <tr>

			                            	<?php $__currentLoopData = $deparments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			                                <td>
			                                	<table class="table table-bordered table-hover">
											        <tr>
											        	<?php $fileTypes = array('fileno'=>'File No','amount'=>'Amount'); ?>
											        	<?php $__currentLoopData = $fileTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fkey=> $fileType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											          		<td><b><?php echo e($fileType); ?></b></td>
											         	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											        </tr>
											        
											        
											        	<?php 
                          if($requestdata['type'] == "Individual") {

                                $clien_id = array();
                                $clien = DB::table('clients')->where('tel_name',$requestdata['individual'])->get();
                                $clien = json_decode(json_encode($clien),true);
                                foreach($clien as $cli){
                                    array_push($clien_id, $cli['id']);
                                }
                                //dd($clien_id);
                                if(count($client_arr) > 0)
                                {
                                  foreach($client_arr as $cli){
                                    array_push($clien_id, $cli);
                                  }
                                }
                                //dd($clien_id);
                                $from = $requestdata['srt']." 00:00:00";
                                $to = $requestdata['endt']." 23:59:59";

                                $file_id = array();
                                if($requestdata['bank'] != 'all_banks')
                                {
                                  $banks_id = explode(',', $requestdata['bank']);
                                  // dd($banks_id);
                                  $bank_name = array();
                                  $bank_details = DB::table('banks')->whereIn('id',$banks_id)->get();
                                  $bank_details = json_decode(json_encode($bank_details),true);
                                  foreach($bank_details as $banks){
                                    array_push($bank_name, $banks['short_name']);
                                  }
                                  
                                  $filloandets = FileLoanDetail::whereIn('bank_name',$bank_name)->get();
                                  $filloandets = json_decode(json_encode($filloandets),true);
                                  foreach($filloandets as $loandet){
                                    array_push($file_id,$loandet['file_id']);
                                  }

                                }
                                 $from = $requestdata['srt']." 00:00:00";
                                $to = $requestdata['endt']." 23:59:59";
                              
                            if($requestdata['prod'] != ""){
                              $prod_name = explode(',',$requestdata['prod']);

                              if(count($clien_id) > 0)
                              {
                                if($case == 'approved')
                                {
                                  $filedata = File::where(function($query){
                                      $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                  })->whereIn('client_id',$clien_id)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                                } else {
                                  $filedata = File::whereIn('client_id',$clien_id)->where('move_to',$case)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                                }
                              } else {
                                if($case == 'approved')
                                {
                                  $filedata = File::where(function($query){
                                      $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                  })->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                                } else {
                                  $filedata = File::where('move_to',$case)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                                }
                              }
                              
                            }else{
                              if(count($clien_id) > 0)
                              {
                                if($case == 'approved')
                                {
                                  $filedata = File::where(function($query){
                                      $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                  })->whereIn('client_id',$clien_id)->whereBetween('created_at', [$from, $to]);
                                } else {
                                  $filedata = File::whereIn('client_id',$clien_id)->where('move_to',$case)->whereBetween('created_at', [$from, $to]);
                                }
                              } else {
                                if($case == 'approved')
                                {
                                  $filedata = File::where(function($query){
                                      $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                  })->whereIn('client_id',$clien_id)->whereBetween('created_at', [$from, $to]);
                                } else {
                                  $filedata = File::whereIn('client_id',$clien_id)->where('move_to',$case)->whereBetween('created_at', [$from, $to]);
                                }
                              }

                            }

                            if($requestdata['bank'] != 'all_banks')
                            {
                              $filedata = $filedata->whereIn('id',$file_id)->get();
                            } else {
                              $filedata = $filedata->get();
                            }
                            $filedata = json_decode(json_encode($filedata),true);

                         
                        } elseif($requestdata['type'] == "Team Wise"){
                        	//$cli_id = Employee::fileteamreport($requestdata['team']);
                          $cli_id = $client_arr;


                        	$from = $requestdata['srt']." 00:00:00";
                             $to = $requestdata['endt']." 23:59:59";

                          $file_id = array();
                          if($requestdata['bank'] != 'all_banks')
                          {
                            $banks_id = explode(',', $requestdata['bank']);
                            // dd($banks_id);
                            $bank_name = array();
                            $bank_details = DB::table('banks')->whereIn('id',$banks_id)->get();
                            $bank_details = json_decode(json_encode($bank_details),true);
                            foreach($bank_details as $banks){
                              array_push($bank_name, $banks['short_name']);
                            }
                            
                            $filloandets = FileLoanDetail::whereIn('bank_name',$bank_name)->get();
                            $filloandets = json_decode(json_encode($filloandets),true);
                            foreach($filloandets as $loandet){
                              array_push($file_id,$loandet['file_id']);
                            }

                          }

                        	if($requestdata['prod'] != ""){
                        		$prod_name = explode(',',$requestdata['prod']);
                            if(count($cli_id) > 0)
                            {
                              if($case == 'approved')
                              {
                                $filedata = File::where(function($query){
                                    $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                })->whereIn('client_id',$cli_id)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                              } else {
                                $filedata = File::whereIn('client_id',$cli_id)->where('move_to',$case)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                              }
                            } else {
                              if($case == 'approved')
                              {
                                $filedata = File::where(function($query){
                                    $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                })->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                              } else {
                                $filedata = File::where('move_to',$case)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                              }
                            }
                             
                        	} else {
                            if(count($cli_id) > 0)
                            {
                              if($case == 'approved')
                              {
                                $filedata = File::where(function($query){
                                    $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                })->whereIn('client_id',$cli_id)->whereBetween('created_at', [$from, $to]);
                              } else {
                                $filedata = File::whereIn('client_id',$cli_id)->where('move_to',$case)->whereBetween('created_at', [$from, $to]);
                              }
                            } else {
                              if($case == 'approved')
                              {
                                $filedata = File::where(function($query){
                                    $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                                })->whereBetween('created_at', [$from, $to]);
                              } else {
                                $filedata = File::where('move_to',$case)->whereBetween('created_at', [$from, $to]);
                              }
                            } 
                         }

                          if($requestdata['bank'] != 'all_banks')
                          {
                            $filedata = $filedata->whereIn('id',$file_id)->get();
                          } else {
                            $filedata = $filedata->get();
                          }
                         $filedata = json_decode(json_encode($filedata),true);
                        	
                        }elseif($requestdata['type'] == "All Branches"){
                            
                           $from = $requestdata['srt']." 00:00:00";
                           $to = $requestdata['endt']." 23:59:59";
                        	
                          $file_id = array();
                          if($requestdata['bank'] != 'all_banks')
                          {
                            $banks_id = explode(',', $requestdata['bank']);
                            // dd($banks_id);
                            $bank_name = array();
                            $bank_details = DB::table('banks')->whereIn('id',$banks_id)->get();
                            $bank_details = json_decode(json_encode($bank_details),true);
                            foreach($bank_details as $banks){
                              array_push($bank_name, $banks['short_name']);
                            }
                            
                            $filloandets = FileLoanDetail::whereIn('bank_name',$bank_name)->get();
                            $filloandets = json_decode(json_encode($filloandets),true);
                            foreach($filloandets as $loandet){
                              array_push($file_id,$loandet['file_id']);
                            }

                          }

                        	if($requestdata['prod'] != ""){
                        		$prod_name = explode(',',$requestdata['prod']);
                            if($case == 'approved')
                            {
                              $filedata = File::where(function($query){
                                  $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                              })->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                            } else {
                        		  $filedata = File::where('move_to',$case)->whereIn('loan_type',$prod_name)->whereBetween('created_at', [$from, $to]);
                            }
                        	    
                        	}
                           else{
                            if($case == 'approved')
                            {
                              $filedata = File::where(function($query){
                                  $query->Where('move_to', 'partially')->orWhere('move_to', 'disbursement')->orWhere('move_to', 'approved');
                              })->whereBetween('created_at', [$from, $to]);
                            } else {
                              $filedata = File::where('move_to',$case)->whereBetween('created_at', [$from, $to]);
                            }
                          }
                              
                          if($requestdata['bank'] != 'all_banks')
                          {
                            $filedata = $filedata->whereIn('id',$file_id)->get();
                          } else {
                            $filedata = $filedata->get();
                          }
                          $filedata = json_decode(json_encode($filedata),true);
                        }

?>

<?php
//$totalfiles = count($filedata);
$totalfiles = 0;
$sum = 0;
?>
<?php $__currentLoopData = $filedata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filedet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
  if($case == 'approved')
  {
    $filedata = FileDisbursement::where(function($query){
        $query->where('disb_type', 'disbursed')
        ->orWhere('disb_type', 'partially_disbursed');
    })
    ->where('file_id', $filedet['id'])->count();
    //echo $filedet['id']."-".$filedata."<br/>";
    if($filedata > 0)
    {
      $totalfiles++;
      $sum = $sum + $filedet['loan_amount'];
      ?>
      <tr>
      <td><?php echo e($filedet['file_no']); ?> </td>
      <td><b>Rs. <?php echo e($filedet['loan_amount']); ?></b></td>
        </tr>
      <?php
    } else {
      if($filedet['move_to'] != 'disbursement' && $filedet['move_to'] != 'partially')
      {
        $totalfiles++;
        $sum = $sum + $filedet['loan_amount'];
        ?>
        <tr>
        <td><?php echo e($filedet['file_no']); ?> </td>
        <td><b>Rs. <?php echo e($filedet['loan_amount']); ?></b></td>
          </tr>
        <?php
      }
    }
  } else if($case == 'partially') {
    $filedata = FileDisbursement::where('file_id',$filedet['id'])->where('disb_type','partially')->count();
    if($filedata > 0)
    {
      $partial_amount = PartialFile::where('file_id',$filedet['id'])->value('partial_amount');
      $totalfiles++;
      //$sum = $sum + $filedet['loan_amount'];
      $sum = $sum + $partial_amount;
      ?>
      <tr>
      <td><?php echo e($filedet['file_no']); ?> </td>
      <td><b>Rs. <?php echo e($partial_amount); ?></b></td>
        </tr>
      <?php
    }
  } else if($case == 'disbursement'){
    $filedata = FileDisbursement::where('file_id',$filedet['id'])->where('disb_type','Fully Disbursed')->count();
    if($filedata > 0)
    {
      $disbursed_amount = FileDisbursement::where('file_id',$filedet['id'])->where('disb_type','Fully Disbursed')->value('amount');
      $totalfiles++;
      //$sum = $sum + $filedet['loan_amount'];
      $sum = $sum + $disbursed_amount;
      ?>
      <tr>
      <td><?php echo e($filedet['file_no']); ?> </td>
      <td><b>Rs. <?php echo e($disbursed_amount); ?></b></td>
        </tr>
      <?php
    }
  } else {
    $totalfiles = count($filedata);
    $sum = $sum + $filedet['loan_amount'];
    ?>
    <tr>
    <td><?php echo e($filedet['file_no']); ?> </td>
    <td><b>Rs. <?php echo e($filedet['loan_amount']); ?></b></td>
      </tr>
    <?php
  }
?>

<?php
  //$sum = $sum + $filedet['loan_amount'];
?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


									        
<tr>

											            			        	
											        	<td colspan="2" class="text-center"><b> Total Files : <?php echo e($totalfiles); ?> <br>Total Amount : Rs. <?php echo e($sum); ?></b></td>
											        </tr>
											    </table>
			                                </td>
			                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			                            </tr>
			                        </tbody>
			                    </table>

			                </div>
			            </div>
			        </div>
			        <!-- END BORDERED TABLE PORTLET-->
			    </div>
               
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout.backendLayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
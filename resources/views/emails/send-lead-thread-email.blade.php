<html>
    <head>
        <style type='text/css'>
            <!--
                .style1 {
                    color: #FFFFFF
                }
                .style2 {
                    font-size: 11px;
                    font-weight: bold;
                    text-decoration: none;
                    font-family: Verdana, Arial, Helvetica, sans-serif;
                    color:#666666;
                }
                .style3 {
                    text-decoration: none;
                    font-family: Verdana, Arial, Helvetica, sans-serif;
                    font-size: 11px;
                    color:#666666;
                }
                -->
        </style>
    </head>
    <body>
        <table width='80%' border='0' cellpadding='3' cellspacing='3' style='border:#EFEFEF 5px solid; padding:5px;'>
            <tr>
                <td colspan='3'></td>
            </tr>
            <tr>
                <td align='left' valign='middle'><img width="60px" border='0' src="{{ asset('images/logo.png') }}" style="width: 100px;"  /></td>
            </tr>
            <tr>
                <td align='left' valign='top' class='style3'><span class='style2'>Date :</span> <?php echo date('d F Y h:i:sa'); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class='style2'>Hi ! Status of {{$leadid}} has been updated to {{ucwords($status)}}. Details are below:-</td>
            </tr>
             <tr>
                <td>&nbsp;</td>
            </tr>           
            <tr>
               <td align='left' valign='middle'>
                   <table width='98%' border='0' align='right' cellpadding='5' cellspacing='5' style='background-color:#F5F5F5'>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Lead Id</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $leadid }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Company Name</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $getLeadDetails['company_name'] }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Contact Person</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $getLeadDetails['contact_person'] }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Description</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $threadmessage }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Status</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ucwords($status) }}</td>
                        </tr>
                        @if(!empty($appointmentDatetime))
                            <tr>
                               <td width='30%' align='left' valign='top' class='style2'>Appointment Date & Time </td>
                               <td width='5%' align='left' valign='top' class='style2'>:</td>
                               <td width='65%' align='left' valign='top' class='style3'><?php echo date('d F Y h:i:sa',strtotime($appointmentDatetime)); ?></td>
                            </tr>
                        @endif
                        @if(!empty($referto))
                            <tr>
                               <td width='30%' align='left' valign='top' class='style2'>Refer To </td>
                               <td width='5%' align='left' valign='top' class='style2'>:</td>
                               <td width='65%' align='left' valign='top' class='style3'><?php echo $referto; ?></td>
                            </tr>
                        @endif
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Status Updated By</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $submitby }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Lead Link</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'><a href="{{url('/s/admin/update-lead-status/'.$actualleadid.'?r=ld')}}">Click here</a></td>
                        </tr>
                   </table>
               </td>
           </tr>
           
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td  class='style2'> Regards<br/>
                    Team Express Paisa!
                </td>
            </tr>
        </table>
    </body>
</html>
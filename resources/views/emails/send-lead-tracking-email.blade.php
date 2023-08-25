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
                <td class='style2'>{{$messagetitle}}</td>
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
                        @if(isset($current_status))
                            <tr>
                               <td width='30%' align='left' valign='top' class='style2'>Current Status</td>
                               <td width='5%' align='left' valign='top' class='style2'>:</td>
                               <td width='65%' align='left' valign='top' class='style3'><?php echo $current_status;  ?></td>
                            </tr>
                        @endif
                        @if(isset($appoint_date_time))
                            <tr>
                               <td width='30%' align='left' valign='top' class='style2'>Last Appointment Date & Time</td>
                               <td width='5%' align='left' valign='top' class='style2'>:</td>
                               <td width='65%' align='left' valign='top' class='style3'><?php echo date('Y-m-d h:ia',strtotime($appoint_date_time));  ?></td>
                            </tr>
                        @endif
                        @if(isset($statuslink))
                            <tr>
                               <td width='30%' align='left' valign='top' class='style2'>Update Status Link</td>
                               <td width='5%' align='left' valign='top' class='style2'>:</td>
                               <td width='65%' align='left' valign='top' class='style3'><?php echo $statuslink;  ?></td>
                            </tr>
                        @endif
                        @if(isset($allocationlink))
                            <tr>
                               <td width='30%' align='left' valign='top' class='style2'>Allocate Link</td>
                               <td width='5%' align='left' valign='top' class='style2'>:</td>
                               <td width='65%' align='left' valign='top' class='style3'><?php echo $allocationlink;  ?></td>
                            </tr>
                        @endif
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
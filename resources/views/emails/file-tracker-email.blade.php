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
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align='left' valign='top' class='style3'><span class='style2'>Date :</span> <?php echo date('d F Y h:i:sa'); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class='style2'>Hello! Bank {{$type}} status has been updated successfully. Details are below:-</td>
            </tr>
             <tr>
                <td>&nbsp;</td>
            </tr>           
            <tr>
               <td align='left' valign='middle'>
                   <table width='98%' border='0' align='right' cellpadding='5' cellspacing='5' style='background-color:#F5F5F5'>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>File No.</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $filedetails['file_no'] }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Client Name</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $clientdetails->name }}</td>
                        </tr>
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Status</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $details['status'] }}</td>
                        </tr>
                        @if(!empty($details['date']))
                            <tr>
                                <td width='30%' align='left' valign='top' class='style2'>{{$type}} Date</td>
                                <td width='5%' align='left' valign='top' class='style2'>:</td>
                                <td width='65%' align='left' valign='top' class='style3'>{{ date('d M Y',strtotime($details['date'])) }}</td>
                            </tr>
                        @endif
                        @if(!empty($details['amount']))
                            <tr>
                                <td width='30%' align='left' valign='top' class='style2'>{{$type}} Date</td>
                                <td width='5%' align='left' valign='top' class='style2'>:</td>
                                <td width='65%' align='left' valign='top' class='style3'>{{ $details['amount'] }}</td>
                            </tr>
                        @endif
                        <tr>
                           <td width='30%' align='left' valign='top' class='style2'>Comments</td>
                           <td width='5%' align='left' valign='top' class='style2'>:</td>
                           <td width='65%' align='left' valign='top' class='style3'>{{ $details['comments'] }}</td>
                        </tr>
                   </table>
               </td>
           </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td  class='style2'> Regards Express Paisa!</td>
            </tr>
        </table>
    </body>
</html>
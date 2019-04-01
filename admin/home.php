<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include ("config/configuration.php");
session_start();
ob_start();

if($loggedin = logged_inadmin()){ // Check if they are logged in


if(isset($_POST['nilai'])){
    $_SESSION['nilai_login'] = $_POST['nilai']+1;
}else{
    $_SESSION['nilai_login'] = 0;
}



$titlebar ="Dashboard";
$titlepage="Dashboard";
$menu="";
$user=  ''.$loggedin['firstname'].' '.$loggedin['lastname'].'';

$content = '

          <!-- END JUMBOTRON -->
          <!-- START CONTAINER FLUID -->
          <div class="container sm-padding-10 no-padding">
            <!-- START ROW -->
            <div class="row">
              <div class="col-lg-3 col-sm-6 d-flex flex-column">
                <!-- START WIDGET widget_map_sales-->
                <!-- START ITEM -->
                
                <!-- END ITEM -->
                <!-- END WIDGET -->
                <!-- START WIDGET widget_weekly_sales_card-->
                
                <!-- END WIDGET -->
                <!-- START WIDGET widget_weekly_sales_card-->
                
                <!-- END WIDGET -->
              </div>
              <div class="col-lg-3 col-sm-6 d-flex flex-column">
                <!-- START ITEM -->
               
                <!-- END ITEM -->
                <!-- START ITEM -->
                
                <!-- END ITEM -->
              </div>
              <div class="col-lg-6 m-b-10 d-flex flex-column">
                <!-- START WIDGET widget_tableRankings-->
                
                <!-- END WIDGET -->
              </div>
            </div>
            <!-- END ROW -->
            <!-- START ROW -->
            <div class="row m-b-30">
              
              <div class="col-lg-6 sm-m-t-10 d-flex align-items-stretch">
                <!-- START WIDGET widget_pendingComments.tpl-->
                <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle d-flex flex-column align-self-stretch">
                  <div class="card-header top-right">
                    <div class="card-controls">
                      <ul>
                        <li><a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i
										class="portlet-icon portlet-icon-refresh"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="padding-25">
                    <div class="pull-left">
                      <h2 class="text-success no-margin">MEMBER</h2>
                      <p class="no-margin">Last Active User</p>
                    </div>
					';
					
						$sql_member = mysql_query("SELECT `member`.*, `users`.`username`, `users`.`email`, `users`.`id_user` FROM `member`, `users`  WHERE `users`.`id_member`=`member`.`id_member`AND `member`.`level` = '0' ORDER BY `member`.`id_member` DESC;");
						
			$sql_sum_member = mysql_num_rows(mysql_query("SELECT `member`.*, `users`.`username`, `users`.`email` FROM `member`, `users` WHERE `users`.`id_member`=`member`.`id_member`AND `member`.`level` = '0' ORDER BY `member`.`id_member` DESC;"));
					
					
					$content .='
                    <h3 class="pull-right semi-bold"><sup>
							<small class="">total</small>
						</sup> '.$sql_sum_member.'
						</h3>
                    <div class="clearfix"></div>
                  </div>
                  <div class="auto-overflow widget-11-2-table">
                    <table class="table table-condensed table-hover">
                      <tbody>
					  ';
					  
					    while ($row_member = mysql_fetch_array($sql_member)){
					  $sql_row_member = mysql_query("SELECT *  FROM `sessions` WHERE `sessions`.`uid`= '".$row_member["id_user"]."'  ;");
						
						$row_date = mysql_fetch_array($sql_row_member);
					  
					  
					 $content .='
                        <tr>
                          <td class="font-montserrat all-caps fs-12" style="width:15%">'.$row_member["username"].'</td>
                          <td class="text-right b-r b-dashed b-grey" style="width:30%">
                            <span class="hint-text small">'.$row_member["email"].'</span>
                          </td>
                          <td class="text-right b-r b-dashed b-grey" style="width:20%">
                            <span class="hint-text small">'.$row_member["lastname"].'</span>
                          </td>
                          <td class="" style="width:35%">
                            <span class="hint-text small">'.$row_date["waktu"].'</span>
                          </td>
                        </tr>
                        ';
						}
						
                        $content .='
                      </tbody>
                    </table>
                  </div>
                  <div class="padding-25 mt-auto">
                    <p class="small no-margin">
                      <a href="#"><i class="fa fs-16 fa-arrow-circle-o-down text-success m-r-10"></i></a>
                      <span class="hint-text ">List Member</span>
                    </p>
                  </div>
                </div>
                <!-- END WIDGET -->
              </div>
			  ';
					
						$sql_item = mysql_query("SELECT `item`.*, `category`. `category` , `category`.`id_cat` FROM `category` , `item`  WHERE `item`.`id_cat`=`category`.`id_cat` AND `item`.`level` = '0' ORDER BY `item`.`id_item` DESC  ;");
						
			$sql_sum_item = mysql_num_rows(mysql_query("SELECT `item`.*, `category`.`category`, `category`.`id_cat` FROM `category`, `item`  WHERE `item`.`id_cat`=`category`.`id_cat` AND `item`.`level` = '0' ORDER BY `item`.`id_item` DESC  ;"));
					
					$content .='
			  
			  <div class="col-lg-6 sm-m-t-10 d-flex align-items-stretch">
                <!-- START WIDGET widget_pendingComments.tpl-->
                <div class="widget-11-2 card no-border card-condensed no-margin widget-loader-circle d-flex flex-column align-self-stretch">
                  <div class="card-header top-right">
                    <div class="card-controls">
                      <ul>
                        <li><a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i
										class="portlet-icon portlet-icon-refresh"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
				  
                  <div class="padding-25">
                    <div class="pull-left">
                      <h2 class="text-success no-margin">Product</h2>
                      <p class="no-margin">Last Transaction</p>
                    </div>
                    <h3 class="pull-right semi-bold"><sup>
							<small class="">total</small>
						</sup> '.$sql_sum_item.'
						</h3>
                    <div class="clearfix"></div>
                  </div>
				  
				  
                  <div class="auto-overflow widget-11-2-table">
                    <table class="table table-condensed table-hover">
                      <tbody>
					  
					  ';
				  
				 while ($row_item = mysql_fetch_array($sql_item)){
		 
					 $content .='
                        <tr>
                          <td class="font-montserrat all-caps fs-12" style="width:35%">'.$row_item["title"].'</td>
                          <td class="text-right b-r b-dashed b-grey" style="width:35%">
                            <span class="hint-text small">'.$row_item["category"].'</span>
                          </td>
                          <td class="text-right b-r b-dashed b-grey" style="width:10%">
                            <span class="hint-text small">'.$row_item["price"].'</span>
                          </td>
                          <td class="" style="width:20%">
                            <span class="hint-text small">'.$row_item["date_add"].'</span>
                          </td>
                        </tr>';
                        }
						
						
						
						$content .='
                      </tbody>
                    </table>
                  </div>
                  <div class="padding-25 mt-auto">
                    <p class="small no-margin">
                      <a href="#"><i class="fa fs-16 fa-arrow-circle-o-down text-success m-r-10"></i></a>
                      <span class="hint-text ">Show more details</span>
                    </p>
                  </div>
                </div>
                <!-- END WIDGET -->
              </div>
			  
			  
            </div>
            <!-- END ROW -->
          </div>
          <!-- END CONTAINER FLUID -->
        </div>
        <!-- END PAGE CONTENT -->
        <!-- START COPYRIGHT -->
        <!-- START CONTAINER FLUID -->
        <!-- START CONTAINER FLUID -->
       



';

$plugin ='

';
$template = admin_template($content,$titlebar,$titlepage,$user,$menu,$plugin);
echo $template;

}else {
    header( 'Location: logout.php' ) ;
}

?>
<?php
include ("config/configuration.php");

session_start();
ob_start();

if($loggedin = logged_in()){
	if(isset($_POST['nilai'])){
	    $_SESSION['nilai_login'] = $_POST['nilai']+1;
	}else{
	    $_SESSION['nilai_login'] = 0;
	}

	$titlebar ="Dashboard";
	$titlepage="Dashboard Member Page";

	$menu="";
	$user=  ''.$_SESSION['user'].'';

  $sql_transaction = mysql_query("SELECT * FROM `transaction` where `id_member` ='".$loggedin["id_member"]."' ORDER BY `date_add`");
    $sql_wishlist = mysql_query("SELECT `wishlist`.*, `item`.`title` FROM `wishlist`, `item` 
        where `wishlist`.`code` = `item`.`code` 
        AND `wishlist`.`id_member` ='" . $loggedin["id_member"] . "'
        AND `wishlist`.`level` = '0'
        ORDER BY `id_wishlist` DESC;");


    $sql_member   = mysql_query("SELECT * FROM `member` where `id_member` ='".$loggedin["id_member"]."' ");
  $row_member   = mysql_fetch_array($sql_member);
  $rows       = mysql_num_rows($sql_transaction);

$content = '
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
              
              <div class="col-lg-5 sm-m-t-10 d-flex align-items-stretch">
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
                      <h2 class="text-success no-margin">Cart</h2>
                      <p class="no-margin">My Cart</p>
                    </div>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="auto-overflow widget-11-2-table">
                    <table class="table table-condensed table-hover">
                      <tbody>';

                      while($row_wishlist = mysql_fetch_array($sql_wishlist)){

                        $content .='
                        <tr>
                          <td class="font-montserrat all-caps fs-12 w-50">'.$row_wishlist['title'].'</td>
                          <td class="text-right b-r b-dashed b-grey w-25">
                            <span class="hint-text small">$'.$row_wishlist['price'].'</span>
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
			  
			  
			  <div class="col-lg-5 sm-m-t-10 d-flex align-items-stretch">
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
                      <h2 class="text-success no-margin">Transactions</h2>
                      <p class="no-margin">Last Transaction</p>
                    </div>
                    <h3 class="pull-right semi-bold"><sup>
							<small class="">total</small>
						</sup> '.$rows.'
						</h3>
                    <div class="clearfix"></div>
                  </div>
                  <div class="auto-overflow widget-11-2-table">
                    <table class="table table-condensed table-hover">
                      <tbody>';
                        while($row_transaction = mysql_fetch_array($sql_transaction)){

                        $content.='
                            <tr>
                              <td class="font-montserrat all-caps fs-12 w-50">'.$row_transaction['kode_transaction'].'</td>
                              <td class="text-right b-r b-dashed b-grey w-25">
                                <span class="hint-text small">$'.$row_transaction['total'].'</span>
                              </td>
                              <td class="w-25">
                                <span class="font-montserrat fs-18">'.$row_transaction['status'].'</span>
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

';

$plugin ='
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());

  gtag("config", "UA-131936719-1");
</script>



';

$template = admin_template($content,$titlebar,$titlepage,$user,$menu,$plugin);
echo $template;

}else{
 header('location:../login.php');			
}
?>
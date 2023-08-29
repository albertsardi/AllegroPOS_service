<?php
    $id='DO'; //debug
    if(!isset($id)) $id='';
  switch($id) {
    case 'PI':
      $selmenu='Purchases.Purchase Invoices'; break;
    case 'AP':
      $selmenu='Purchases.Pay Bills'; break;
    case 'DO':
      $selmenu='Sales.Delivery Order'; break;
    case 'SI':
      $selmenu='Sales.Sales Invoices'; break;
    case 'AR':
      $selmenu='Sales.Receive Payments'; break;

    case '':
      $selmenu='Dashboard.'; break;
    case 'customer':
      $selmenu='Customers.'; break;
    case 'supplier':
      $selmenu='Suppliers.'; break;
    case 'product':
      $selmenu='Products.'; break;
  }
 
?>

<div class="left main-sidebar">

    <div class="sidebar-inner leftscroll">

        <div id="sidebar-menu">

            <ul>


                <!--<li class="submenu">
          <a class="active" href="index.php"><i class="fa fa-fw fa-bars"></i><span> Dashboard </span> </a>
                  </li>-->
                <?= headmenu('Dashboard', 'app/dashboard', 'fa-bars');?>
                <?= headmenu('Reports', 'app/reportall', 'fa-area-chart');?>
                <?= headmenu('Cash & Banks', 'datalist/bank', 'fa-file-text-o');?>
                <?= headsubmenu('Purchases', 'fa-th', [
                ['Purchase Order','translist/PO'],
                ['Purchase Invoices','translist/PI'],
                ['Pay BIlls','translist/AP']
              ]);?>
                <?= headsubmenu('Sales', 'fa-th', [
                ['Sales Order','translist/SO'],
                {{-- ['Delivery Order','translist/DO'], --}}
                ['Sales Invoices','translist/SI'],
                ['Receive Payments','translist/AR']
              ]);?>
                <?= headmenu('Expenses', 'translist/EX', 'fa-file-text-o');?>
                <?= headmenu('Customers', 'datalist/customer', 'fa-file-text-o');?>
                <?= headmenu('Suppliers', 'datalist/supplier', 'fa-file-text-o');?>
                <?= headmenu('Products', 'datalist/product', 'fa-file-text-o');?>
                <?= headmenu('Chart of Accounts', 'datalist/coa', 'fa-file-text-o');?>
                <?= headmenu('Setting', 'app/setting', 'fa-file-text-o');?>
                <?= headmenu('Log out', 'app/logout', 'fa-file-text-o');?>










            </ul>

            <div class="clearfix"></div>

        </div>

        <div class="clearfix"></div>

    </div>

</div>

<?php
  function headmenu($text, $link, $faicon) {
    global $selmenu; $sel=explode('.' , $selmenu);
    $sel=($text==$sel[0])?"class='active'":"";
    //$link=$_SERVER['SERVER_NAME'];
    //$link=$_SERVER['SERVER_ADDR'];
    $link=getLink($link);
    return "<li class='submenu'>
              <a href='$link' $sel>
                <i class='fa fa-fw $faicon'></i><span> $text </span> 
              </a>
            </li>";
  }

  function headsubmenu($text, $faicon, $arr=[]) {
    global $selmenu; $sel=explode('.' , $selmenu);
    if(!isset($sel[1])) $sel[1]='';
    $s=($text==$sel[0])?"class='active'":"";
    $result="<li class='submenu'>
              <a href='#' $s><i class='fa fa-fw $faicon'></i> <span> $text </span> <span class='menu-arrow'></span></a>
              <ul class='list-unstyled'>";
                for($a=0;$a<count($arr);$a++){
                  $text = $arr[$a][0];
                  $link=getLink($arr[$a][1]);
                  $s=($text==$sel[1])?"class='active'":"";
                  $result.="<li><a href='$link' $s> $text </a></li>";  
                }
              $result.="</ul>
            </li>";
    return $result;
  }

function getLink($link) {
    //translist/PI
    $link=explode('/', $link);
    if($link[0]=='datalist') {
        $slink=URL::action('DatalistController@datalist', $link[1]);
        return $slink;
    }
    if($link[0]=='translist') {
        $slink=URL::action('DatalistController@translist', $link[1]);
        return $slink;
    }
    if($link[0]=='app') {
        $slink=URL::action('AppController@'.$link[1]);
        return $slink;
    }
    return '';
}

?>
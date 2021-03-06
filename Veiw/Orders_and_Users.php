<?php 
if(!isset($_SESSION))
{
	session_start();
}
if(!isset($_SESSION['admin']))
{
     header('Location: ' . filter_var('../Veiw/home.php', FILTER_SANITIZE_URL));
}
include_once '../Models/orderClass.php';
include_once '../Models/userClass.php';
$order = new Order();
$user = new User();
$OrdersNumber = $order->getNumberOfOrders();
$UsersNumber = $user->getNumberOfUsers();
$OrdersPages = ceil($OrdersNumber/10);
$UsersPages = ceil($UsersNumber/10);
if (!isset($_GET['pageOrder'])) {
    $pageOrder= 1;
}
elseif($_GET['pageOrder']>$OrdersPages)
{
    header('Location: '.filter_var('Orders_and_Users.php', FILTER_SANITIZE_URL));
}
else
{
    $pageOrder = (int)$_GET['pageOrder'];
}
if (!isset($_GET['pageUser'])) {
    $pageUser = 1;
}
elseif($_GET['pageUser']>$UsersPages)
{
    header('Location: '.filter_var('Orders_and_Users.php', FILTER_SANITIZE_URL));
}
else
{
    $pageUser = (int)$_GET['pageUser'];
}
include_once '../Models/productClass.php';
$product = new Product();

$Orderdata = $order->GetOrdersByLIMIT($pageOrder);
$dataUser = $user->GetUsersByLIMIT($pageUser);
include 'NavBar.php'; 
?>

<div class="container-fluid ">
    <div class="row content">
        <h2 class="page-header">Products</h2>
        <div class="col-sm-3 sidenav">
            <h4>Categories</h4>
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#orders"  data-toggle="pill">Current Orders</a></li>
                <li><a href="#users"  data-toggle="pill">Current Users</a></li>
            </ul><br>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Order..">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-sm-9 tab-content">
            <div id="orders" class="list-group tab-pane fade in active">
                <?php
                foreach($Orderdata as $value)
                {
                    
                    $productData=$product->getProduct($value['product']);
                    echo '<span class="list-group-item products_" style="overflow: hidden">
                    '.$productData[0]["name_en"].'
                    <a href="../Controllers/deleteOrderController.php?id='.$value["id"].'" id="" class="btn btn-danger" role="button" style="float: right">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>
                    <button id="editButton" class="btn btn-info" role="button" data-toggle="modal" data-target="#'.$value["id"].'" style="float: right; margin-right: 10px">
                        <i class="glyphicon glyphicon-info-sign"></i>
                    </button>
                    </span>';
                }
                ?>
                <div class="pages_number text-center">
                    <ul class="pagination">
                        <?php
                        $previousPage = (int)$pageOrder-1;
                        $nextPage = (int)$pageOrder+1;
                        if($pageOrder > 1)
                        {
                         echo'<li class="previous "><a href="Orders_and_Users.php?pageOrder='.$previousPage.'&pageUser='.$pageUser.'">previous</a></li>';
                        }
                        $max = 7;
                        if($pageOrder< $max)
                        {
                            $sp = 1;
                        }   
                        elseif($pageOrder >= ($OrdersPages - floor($max / 2)) )
                        {
                            $sp = $OrdersPages - $max+2;
                        }
                        elseif($pageOrder >= $max)
                        {
                            $sp = $pageOrder  - floor($max/2);
                        }
                        if($pageOrder >= $max)
                        {
                            echo'<li><a href="Orders_and_Users.php?pageOrder=1&pageUser='.$pageUser.'">1</a></li>';
                            echo'<li><a >...</a></li>';
                        }
                        for($i = $sp; $i <= ($sp + $max -1);$i++)
                        {
                            if($i > $OrdersPages)
                            {
                                continue;
                            }
                            if($pageOrder == $i)
                            {
                                echo'<li class="active"><a href="#">'.$pageOrder.'</a></li>';
                            }
                            else
                            {
                                echo'<li><a href="Orders_and_Users.php?pageOrder='.$i.'&pageUser='.$pageUser.'">'.$i.'</a></li>';
                            }
                        }
                        if($pageOrder  < ($OrdersPages - floor($max / 2))-1)
                        {
                            echo'<li><a >...</a></li>';
                        }
                        if($pageOrder  < ($OrdersPages - floor($max / 2))) 
                        {
                            echo'<li><a href="Orders_and_Users.php?pageOrder='.$OrdersPages.'&pageUser='.$pageUser.'">'.$OrdersPages.'</a></li>';
                        }
                        if($pageOrder < $OrdersPages)
                        {
                            echo'<li class="next"><a href="Orders_and_Users.php?pageOrder='.$nextPage.'&pageUser='.$UsersPages.'">next</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div><br>

            <div id="users" class="list-group tab-pane fade">
                <?php
                foreach ($dataUser as $value) {
                    
                    if ($value["block"] == 0) {
                        $btn = "warning";
                        
                    }
                    else {
                        $btn = "danger";
                    }
                    echo '<span class="list-group-item products_ " style="overflow: hidden">
                        
                            <a href="" data-toggle="modal" data-target="#'.$value["id"].'user" >'.$value["first_name"].' '.$value["last_name"].'</a>
                            <a class="btn btn-danger" role="button" style="float: right">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                            <a href="../Controllers/blockUserController.php?submit=block&id='.$value["id"].'" class="btn btn-'.$btn.'" role="button" style="float: right; margin-right: 10px">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                            </a>
                        </span>
                ';
                }
                ?>
                <span class="list-group-item products_" style="overflow: hidden">
                    
                    Ahmed Hossam
                    <button class="btn btn-danger" role="button" style="float: right">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>
                    <button class="btn btn-warning" role="button" style="float: right; margin-right: 10px">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                    </button>
                </span>
                <div class="pages_number text-center">
                    <ul class="pagination">
                        <?php
                        $previousPage = (int)$pageUser-1;
                        $nextPage = (int)$pageUser+1;
                        if($pageUser > 1)
                        {
                         echo'<li class="previous "><a href="Orders_and_Users.php?pageUser='.$previousPage.'&pageOrder='.$pageOrder.'">previous</a></li>';
                        }
                        $max = 7;
                        if($pageUser< $max)
                        {
                            $sp = 1;
                        }   
                        elseif($pageUser >= ($UsersPages - floor($max / 2)) )
                        {
                            $sp = $UsersPages - $max+2;
                        }
                        elseif($pageUser >= $max)
                        {
                            $sp = $pageUser  - floor($max/2);
                        } 
                        if($pageUser >= $max)
                        {
                            echo'<li><a href="Orders_and_Users.php?pageUser=1&pageOrder='.$pageOrder.'">1</a></li>';
                            echo'<li><a >...</a></li>';
                        }
                        
                        for($i = $sp; $i <= ($sp + $max -1);$i++)
                        {
                            if($i > $UsersPages)
                            {
                                continue;
                            }
                            if($pageUser == $i)
                            {
                                echo'<li class="active"><a href="#">'.$pageUser.'</a></li>';
                            }
                            else
                            {
                                echo'<li><a href="Orders_and_Users.php?pageUser='.$i.'&pageOrder='.$pageOrder.'">'.$i.'</a></li>';
                            }
                        }
                        if($pageUser  < ($UsersPages - floor($max / 2))-1)
                        {
                            echo'<li><a >...</a></li>';
                        }
                        if($pageUser  < ($UsersPages - floor($max / 2))) 
                        {
                            echo'<li><a href="Orders_and_Users.php?pageUser='.$UsersPages.'&pageOrder='.$pageOrder.'">'.$UsersPages.'</a></li>';
                        }
                        if($pageUser < $UsersPages)
                        {
                            echo'<li class="next"><a href="Orders_and_Users.php?pageUser='.$nextPage.'&pageOrder='.$OrdersPages.'">next</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div><br>
        </div>
        <!-- Model Info -->
        <?php
        foreach($Orderdata as $value)
        {
            $userData=$user->getUser($value['user']);
            echo '<div class="modal fade" id="'.$value["id"].'" role="dialog">
            <div class="modal-dialog modal-md">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body ">
                        <span style="color: green">Date : </span><span>'.$value["dateOfOrder"].'</span><br>
                        <hr>
                        <span style="color: green">Quantity : </span><span> '.$value["quantity"].'</span><br>
                        <hr>
                        <span style="color: green">Full Price : </span><span> 5000 EGP</span><br>
                        <hr>
                        <span style="color: green">Orderes by : </span><a href="" class="" data-dismiss="modal" data-toggle="modal" data-target="#userInfoModal"> '.$userData[0]["first_name"].' '.$userData[0]["last_name"].'</a>
                    </div>
                </div>
            </div>
        </div>';
        }
        ?>
        

         <!-- Model User Info -->
        <?php
        foreach ($dataUser as $value) {
            if ($value["phone"] != '') {
                $phone = '<br><hr><span style="color: green">Mobile : </span><span>'.$value["phone"].'</span>';
            }
            else {
                $phone = '';
            }
            
            if ($value["oauth_provider"] == "facebook") {
                $link = '<br><hr><span style="color: green">Facebook Profile : </span><a href="'.$value["link"].'">'.$value["first_name"].' '.$value["last_name"].'</a>';
            }
            elseif ($value["oauth_provider"] == "google")
            {
                $link = '<br><hr><span style="color: green">Google+ Profile : </span><a href="'.$value["link"].'">'.$value["first_name"].' '.$value["last_name"].'</a>';
            }
            else {
                $link ='';
            }
            echo '<div class="modal fade" id="'.$value["id"].'user" role="dialog">
                    <div class="modal-dialog modal-md">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body ">
                                <span style="color: green">Name : </span><span> '.$value["first_name"].' '.$value["last_name"].'</span><br>
                                <hr>
                                <span style="color: green">Email : </span><span>'.$value["email"].'</span>
                                
                                '.$phone.'
                                '.$link.'
                            </div>
                        </div>
                    </div>
                </div>
';
        }
        ?>

    </div>

    <?php include './footer.php'; ?>
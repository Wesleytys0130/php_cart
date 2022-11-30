<?php
session_start();
$product_ids = array();
// session_destroy(); #重新整理暫存


//購物車按鈕是否被啟動
if(filter_input(INPUT_POST,'add_to_cart')){
  //查看購物車裡面是否有產品
  if(isset($_SESSION['shopping_cart'])){
    //查看購物車裡面有多少產品
    $count = count($_SESSION['shopping_cart']);
    //創建產品列表，讓購物車和產品ID吻合
    $product_ids = array_column($_SESSION['shopping_cart'],'ID');
    //如產品沒有在購物車裡，增加產品
    if (!in_array(filter_input(INPUT_GET,'ID'), $product_ids)){
      $_SESSION['shopping_cart'][$count]= array
      (
      'ID' => filter_input(INPUT_GET,'ID'),
      'Name' => filter_input(INPUT_POST,'Name'),
      'Price' => filter_input(INPUT_POST,'Price'),
      'Quantity' => filter_input(INPUT_POST,'Quantity')
      );
    }
    else{//產品已存在，增加數量
      //將重複產品與購物車的ID吻合後增加數量
      for($i = 0; $i < count($product_ids);$i++){
        if($product_ids[$i] == filter_input(INPUT_GET,'ID')){
          $_SESSION['shopping_cart'][$i]['Quantity'] += filter_input(INPUT_POST, 'Quantity');
        }
      }
    }
  }
  //如果購物車裡面沒有產品
  else{
    //創建第一個產品表，index為0
    $_SESSION['shopping_cart'][0]= array
    (
      'ID' => filter_input(INPUT_GET,'ID'),
      'Name' => filter_input(INPUT_POST,'Name'),
      'Price' => filter_input(INPUT_POST,'Price'),
      'Quantity' => filter_input(INPUT_POST,'Quantity')
    );
  }
}

if(filter_input(INPUT_GET,'action') == 'delete'){
//查找購物車產品，刪除吻合得的ID
  foreach($_SESSION['shopping_cart'] as $key =>$product){
    if($product['ID']==filter_input(INPUT_GET,'ID')){
      unset($_SESSION['shopping_cart'][$key]);
    }
  }
  //重置session，讓$product_ids數量吻合
  $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
}



// pre_r($_SESSION);
function pre_r($array){
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>fruit Cart</title>
    <link rel="stylesheet" href="CSS/reset.css"/>
    <link rel="stylesheet" type="text/css" href="CSS/cart.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>

  <div class="container-fluid row row-cols-1 row-cols-md-4">
    <?php include('db.php');?>
    <?php if($result):
      if(mysqli_num_rows($result)>0):
          while($product = mysqli_fetch_assoc($result)):?>
    <div class="col">
      <form method="post" action="cart.php?action=add&ID=<?php echo $product['ID'];?>">
        <div class="products card h-100">
          <img src="fruit_image/<?php echo $product['Image'];?>" class="card-img-top"/>  
          <h4 class="card-body"><?php echo $product['Name']; ?> </h4>
          <h4 class="card-body">$ <?php echo $product['Price'];?></h4>
          <input type="text" name="Quantity" class="form-control" value="1" />
    
          <input type="hidden" name="Name" value="<?php echo $product['Name']; ?>" />
          <input type="hidden" name="Price" value="<?php echo $product['Price']; ?>" />
          <input type="submit"name="add_to_cart" class="btn btn-info" style="margin-top:5px" value="Add to Cart"/>
        </div>
      </form>
    </div>
    <?php endwhile;endif;endif;?>
  </div>

    
    <div class="table-responsive container-fluid">
    <table class="table">
      <tr><th colspan="5"><h3>Order Details</h3></th></tr>
    <tr>
      <th width="40%">Product Name</th>
      <th width="10%">Quantity</th>
      <th width="20%">Price</th>
      <th width="15%">Total</th>
      <th width="5%">Action</th>
    </tr>
    <?php
    if(!empty($_SESSION['shopping_cart'])):
      $total = 0;
      foreach($_SESSION['shopping_cart'] as $key => $product):
    ?>
    <tr>
      <td><?php echo $product['Name']?></td>
      <td><?php echo $product['Quantity']?></td>
      <td>$ <?php echo $product['Price']?></td>
      <td>$ <?php echo number_format($product['Quantity'] * $product['Price'],2);?></td>
      <td><a href="cart.php?action=delete&ID=<?php echo $product['ID'];?>">
            <div class="btn btn-danger">Remove</div>
          </a>
      </td>
    </tr>
    <?php
          $total = $total +($product['Quantity'] * $product['Price']);
          endforeach;?>
    <tr>
      <td colspan="3" align="right">Total</td>
      <td align="right">$ <?php echo number_format($total,2); ?> </td>
      <td></td>
    </tr>
    <tr>
      <td colspan="5">
      <?php
      if(isset($_SESSION['shopping_cart'])):
      if (count($_SESSION['shopping_cart']) > 0):
      ?>
      <a href="#" class="btn btn-primary">Checkout</a>
      <?php endif; endif; ?>
      </td>
    </tr>
    <?php endif;?>
    </table>
    </div>
  </body>
</html>
<?php
//Define error
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// load lobraries
require_once __DIR__ .'/db.php';
require_once __DIR__ .'/functions.php';

// logic code
session_start();
// session_destroy(); #重新整理暫存

$query = 'SELECT * FROM Products ORDER BY ID ASC';
$result = mysqli_query($conn, $query);
$productIds = [];
$items = $_SESSION['shopping_cart'] ?? [];

#add to cart被啟動
if(filter_input(INPUT_POST,'add_to_cart')){
  #查看購物車裡面是否有產品
  if(isset($items)){ 
    checkCartShop($items);
  }
  #如果購物車裡面沒有產品
  else{
    createCartShop($items);
  }
}

#delete被啟動
if(filter_input(INPUT_GET,'action') == 'delete'){
  delete($items);
}
// $count = count($items);
// echo $count;
// pre_r($items);
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>fruit Cart</title>
    <link rel="stylesheet" href="css/reset.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/cart.css"/>
  </head>
  <body>

    <div class="container-fluid row row-cols-1 row-cols-md-4">
      <?php if($result): ?>
        <?php if(mysqli_num_rows($result)>0):?>
          <?php while($product = mysqli_fetch_assoc($result)):?>
            <div class="col">
              <form method="post" action="cart.php?action=add&ID=<?= $product['ID'];?>">
                <div class="products card h-100">
                  <img src="fruit_image/<?= $product['Image'];?>"class="card-img-top"/>  
                  <h4 class="card-body"><?= $product['Name']; ?></h4>
                  <h4 class="card-body">$ <?= $product['Price'];?></h4>
                  <input type="text" name="Quantity" class="form-control" value="1" />
                  <input type="hidden" name="Name" value="<?= $product['Name']; ?>" />
                  <input type="hidden" name="Price" value="<?= $product['Price']; ?>" />
                  <input type="submit"name="add_to_cart" class="btn btn-info" style="margin-top:5px" value="Add to Cart"/>
                </div>
              </form>
            </div>
          <?php endwhile;?>
        <?php endif;?>
      <?php endif;?>
    </div>

    <div class="table-responsive container-fluid">
      <table class="table">
        <tr>
          <th colspan="5"><h3>Order Details</h3></th></tr>
        <tr>
          <th width="40%">Product Name</th>
          <th width="10%">Quantity</th>
          <th width="20%">Price</th>
          <th width="15%">Total</th>
          <th width="5%">Action</th>
        </tr>
          <?php if(!empty($_SESSION['shopping_cart'])): $total = 0; ?> 
            <?php foreach($_SESSION['shopping_cart'] as $key => $product):?>
              <tr>
                <td><?= $product['Name']?></td>
                <td><?= $product['Quantity']?></td>
                <td>$ <?= $product['Price']?></td>
                <td>$ <?= number_format($product['Quantity'] * $product['Price'],2);?></td>
                <td>
                  <a href="cart.php?action=delete&ID=<?php echo $product['ID'];?>">
                    <div class="btn btn-danger">Remove</div>
                  </a>
                </td>
              </tr>
              <?php $total = $total +($product['Quantity'] * $product['Price']);?>
            <?php endforeach;?>
              <tr>
                <td colspan="3" align="right">Total</td>
                <td align="right">$ <?= number_format($total,2); ?> </td><td></td>
              </tr>
              <tr>
                <td colspan="5">
                  <?php if(isset($_SESSION['shopping_cart'])):?>
                    <?php if (count($_SESSION['shopping_cart']) > 0):?>
                      <a href="#" class="btn btn-primary">Checkout</a>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
          <?php endif;?>
      </table>
    </div>
  </body>
</html>
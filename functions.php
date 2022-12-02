<?php
function pre_r($array){
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}
function createCartShop($array){
    //創建第一個產品表，index為0
    $array[0]= [
      'ID' => filter_input(INPUT_GET,'ID'),
      'Name' => filter_input(INPUT_POST,'Name'),
      'Price' => filter_input(INPUT_POST,'Price'),
      'Quantity' => filter_input(INPUT_POST,'Quantity')
    ];
}
function checkCartShop($array){
    //查看購物車裡面有多少產品
    $count = count($array);
    //創建產品列表，讓購物車和產品ID吻合
    $productIds = array_column($array,'ID');
    //如產品沒有在購物車裡，增加產品
    if (!in_array(filter_input(INPUT_GET,'ID'), $productIds)){
        $_SESSION['shopping_cart'][$count]= [
        'ID' => filter_input(INPUT_GET,'ID'),
        'Name' => filter_input(INPUT_POST,'Name'),
        'Price' => filter_input(INPUT_POST,'Price'),
        'Quantity' => filter_input(INPUT_POST,'Quantity')
        ];
    }
    else{//產品已存在，增加數量
      //將重複產品與購物車的ID吻合後增加數量
      for($i = 0; $i < count($productIds);$i++){
        if($productIds[$i] == filter_input(INPUT_GET,'ID')){
            $_SESSION['shopping_cart'][$i]['Quantity'] += filter_input(INPUT_POST, 'Quantity');
        }
      }
    }
}
function delete($array){
    //查找購物車產品，刪除吻合得的ID
    foreach($array as $key =>$product){
        if($product['ID']==filter_input(INPUT_GET,'ID')){
          unset($_SESSION['shopping_cart'][$key]);
        }
      }
      //重置session，讓$productIds數量吻合
      $array = array_values($array);
}
<?php
// 開啟錯誤偵測模式
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//連接
$server = "localhost";         # MySQL/MariaDB 伺服器
$dbusername = "root";       # 使用者帳號
$dbpassword = "root"; # 使用者密碼
$dbname = "cart";    # 資料庫名稱
$conn = mysqli_connect($server,$dbusername,$dbpassword,$dbname);
// if(!$conn){
//     die("Connection failed" . mysql_connect_error());
// }

$query = 'SELECT * FROM Products ORDER BY ID ASC';
$result = mysqli_query($conn, $query);


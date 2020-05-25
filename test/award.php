<?php
include "./com/base.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>award</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <?php 
    include "./include/header.php"; 
  ?>

  <?php
    if(empty($_GET)){
      echo "請選擇要對獎的項目<a href='invoice.php'>各期獎號</a>";
      exit();
    }

    // 多寫一個資料表放得獎的金額?
    $award_type=[
      // 獎別,第幾獎,需對獎的碼數
      1=>["特別獎",1,8],
      2=>["特獎",2,8],
      3=>["頭獎",3,8],
      4=>["二獎",3,7],
      5=>["三獎",3,6],
      6=>["四獎",3,5],
      7=>["五獎",3,4],
      8=>["六獎",3,3],
      9=>["增開六獎",4,3]
    ];
    $aw=$_GET['aw'];
    echo "獎別：".$award_type[$aw][0]."<br>";
    echo "年份：".$_GET['year']."<br>";
    echo "期別：".$_GET['period']."<br>";

    // 筆數
    $award_nums=nums("award_number",[
      "year"=>$_GET['year'],
      "period"=>$_GET['period'],
      "type"=>$award_type[$aw][1],
    ]);

    

    echo "獎號數量：".$award_nums;
    $award_number=all("award_number",[
      "year"=>$_GET['year'],
      "period"=>$_GET['period'],
      "type"=>$award_type[$_GET['aw']][1],
    ]);

    echo "<h4>對獎獎號</h4>";
    $t_num=[];
    foreach($award_number as $num){
      echo $num['number']."<br>";
      // 將獎號放入陣列
      $t_num[]=$num['number'];
    }

    // if($award_nums>1){
    //   $award_numbers=all("award_number",[
    //     "year"=>$_GET['year'],
    //     "period"=>$_GET['period'],
    //     "type"=>$award_type[$_GET['aw']][1],
    //   ]);
    // }else{
    //   $award_numbers=find("award_number",[
    //     "year"=>$_GET['year'],
    //     "period"=>$_GET['period'],
    //     "type"=>$award_type[$_GET['aw']][1],
    //   ]);
    // }
    // echo "<pre>"; print_r($award_numbers); echo "</pre>";


    echo "<h4>該期發票號碼</h4>";
    //抓出符合當期的發票號碼
    $invoices=all("invoice",[
      "year"=>$_GET['year'],
      "period"=>$_GET['period']
    ]);


    // 比對兩個陣列的資料
    foreach($invoices as $ins){


      foreach($t_num as $tn){

        // 從後面算取幾位數
        $len=$award_type[$aw][2];
        // 起始值
        $start=8-$len;

        // 針對"增開六獎"特別處理substr的開始位置
        if($aw!=9){
          $target_num=mb_substr($tn,$start,$len);
        }
        else{
          $target_num=$tn;
        }

        if(mb_substr($ins['number'],$start,$len) == $target_num){
          echo "<span style='color: red;font-size:20px;'>".$ins['number']."中獎了</span><br>";
        }
        else{
          echo $ins['number']."沒中獎<br>";
        }
      }
    }

  ?>  
  <hr>
  
  



  
</body>
</html>
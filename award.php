<?php
  include "com/base.php";

  // 年份、期別搜尋
  $y=date("Y");
  if(isset($_GET['y'])){
    $y=$_GET['y'];
  }
  // 月份/2 為期數
  $p=ceil(date("n")/2);
  if(isset($_GET['p'])){
    $p=$_GET['p'];
  }

  function func_award($num){
    global $y;
    global $p;
    switch($num){
      case 1:
        // 特別獎
        $rows=all('award_number',['period'=>$p,'year'=>$y,'type'=>1]);
        break;
      case 2:
        // 特獎
        $rows=all('award_number',['period'=>$p,'year'=>$y,'type'=>2]);
        break;
      case 3:
        // 頭獎
        $rows=all('award_number',['period'=>$p,'year'=>$y,'type'=>3]);
        break;
      case 4:
        // 加開四獎
        $rows=all('award_number',['period'=>$p,'year'=>$y,'type'=>4]);
    }
    return $rows;
  }

  // 兌換時間提示
  $displayCss=dateCompare();
  function dateCompare(){
    global $y;
    $y2=$y+1;
    global $p;
    switch($p){
      case 1:
        $deadline=strtotime(date("$y-7-5"));
        break;
      case 2:
        $deadline=strtotime(date("$y-9-5"));
        break;
      case 3:
        $deadline=strtotime(date("$y-11-5"));
        break;
      case 4:
        $deadline=strtotime(date("$y2-1-5"));
        break;
      case 5:
        $deadline=strtotime(date("$y2-3-5"));
        break;
      case 6:
        $deadline=strtotime(date("$y2-5-5"));
        break;
    }

    $today=strtotime(date("Y-m-d"));
    if($today<$deadline){
      // 未超過時間
      return "none";
    }else{
      // 超過時間
      return "block";
    }
  }
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>統一發票管理系統</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/award.css">
  <!-- google icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    p.tip{
      display: <?=$displayCss;?>;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php 
      $pageheader="對獎";
      $navPage="3";
      include "include/header.php"; 
    ?>


    <div class="invoiceBox">

      <div class="sideNav">
        <select name="year" onchange="location=this.value;">
          <?php
            $yToday = date("Y");
            $y1=$yToday-4;
            for($i=$y1;$i<=$yToday;$i++){
              $selected=($y==$i)?'selected':'';
              echo "<option value='award.php?y=$i&p=$p' ".$selected.">".$i."</option>";
            }
          ?>
        </select>
        <ul>
          <li><a class="<?=($p==1)?'active':'';?>" href="award.php?y=<?=$y;?>&p=1">1,2月</a></li>
          <li><a class="<?=($p==2)?'active':'';?>" href="award.php?y=<?=$y;?>&p=2">3,4月</a></li>
          <li><a class="<?=($p==3)?'active':'';?>" href="award.php?y=<?=$y;?>&p=3">5,6月</a></li>
          <li><a class="<?=($p==4)?'active':'';?>" href="award.php?y=<?=$y;?>&p=4">7,8月</a></li>
          <li><a class="<?=($p==5)?'active':'';?>" href="award.php?y=<?=$y;?>&p=5">9,10月</a></li>
          <li><a class="<?=($p==6)?'active':'';?>" href="award.php?y=<?=$y;?>&p=6">11,12月</a></li>
        </ul>
      </div>

      <div class="listBox">
        <div class="topBar">
          <?php
            if($p==0){
              echo "<h3>".$y."年- All</h3>";
            }else{
              echo "<h3>".$y."年-".($p*2-1).",".($p*2)."月</h3>";
            }
          ?>
          <a class="btnDesc" href="javascript:void(0);">對獎說明</a>
        </div>
        <p class=tip>小提醒:此期號碼已超過兌換的時間</p>
        
        <div class="overlay"></div>
        <div class="descBox">
          <div class="topDiv">
            <h3>對獎規則</h3>
            <a class="btnClose material-icons" href="javasrcipt:void(0);">close</a>
          </div>
          <p><span>特別獎:</span>同期統一發票收執聯8位數號碼與特別獎號碼相同者獎金1,000萬元</p>
          <p><span>特獎:</span>同期統一發票收執聯8位數號碼與特獎號碼相同者獎金200萬元</p>
          <p><span>頭獎:</span>同期統一發票收執聯8位數號碼與頭獎號碼相同者獎金20萬元</p>
          <p><span>二獎:</span>同期統一發票收執聯末7位數號碼與頭獎中獎號碼末7位相同者各得獎金4萬元</p>
          <p><span>三獎:</span>同期統一發票收執聯末6位數號碼與頭獎中獎號碼末6位相同者各得獎金1萬元</p>
          <p><span>四獎:</span>同期統一發票收執聯末5位數號碼與頭獎中獎號碼末5位相同者各得獎金4千元</p>
          <p><span>五獎:</span>同期統一發票收執聯末4位數號碼與頭獎中獎號碼末4位相同者各得獎金1千元</p>
          <p><span>六獎:</span>同期統一發票收執聯末3位數號碼與頭獎中獎號碼末3位相同者各得獎金2百元</p>
          <br>
          <h3>開獎與兌換時間</h3>
          <table>
            <tr>
              <td>期別</td>
              <td>開獎時間</td>
              <td>兌換時間</td>
            </tr>
            <tr>
              <td>1、2月</td>
              <td>3/25 13:30</td>
              <td>4/6~7/5</td>
            </tr>
            <tr>
              <td>3、4月</td>
              <td>5/25 13:30</td>
              <td>6/6~9/5</td>
            </tr>
            <tr>
              <td>5、6月</td>
              <td>7/25 13:30</td>
              <td>8/6~11/5</td>
            </tr>
            <tr>
              <td>7、8月</td>
              <td>9/25 13:30</td>
              <td>10/6~1/5</td>
            </tr>
            <tr>
              <td>9、10月</td>
              <td>11/25 13:30</td>
              <td>12/6~3/5</td>
            </tr>
            <tr>
              <td>11、12月</td>
              <td>1/25 13:30</td>
              <td>2/6~5/5</td>
            </tr>
          </table>
        </div>


        <table class="listTable">
          <tr>
            <td>獎項</td>
            <td>獎號</td>
            <td>獎金</td>
          </tr>
          <?php
            $count=0;
            // 獎項清單
            $awardList=all('award_list');

            for($a=1;$a<=4;$a++){
              $rows=func_award($a);

              if($a!=3){
                // 特別獎、特獎、增開六獎
                // 獎項清單索引值
                if($a!=4){
                  $alIndex=$a-1;
                }else{
                  $alIndex=9-1;
                }

                echo "<tr class='tr'>";
                echo "  <td>".$awardList[$alIndex]['award']."</td>";
                echo "  <td>";
                foreach($rows as $row){
                  if(!empty($row['number'])){
                    echo "<p>".$row['number']."</p>";
                    $count=$count+1;
                  }
                }
                echo "  </td>";
                echo "  <td>".$awardList[$alIndex]['bonus']."</td>";
                echo "</tr>";
              }else{
                // 頭獎~六獎
                for($b=3;$b<=8;$b++){
                  $alIndex=$b-1;

                  echo "<tr class='tr'>";
                  echo "  <td>".$awardList[$alIndex]['award']."</td>";
                  if($b==3){
                    // 跨六欄
                    echo "  <td rowspan='6'>";
                    foreach($rows as $row){
                      if(!empty($row['number'])){
                        echo "<p>".$row['number']."</p>";
                        $count=$count+1;
                      }
                    }
                    echo "  </td>";
                  }
                  echo "  <td>".$awardList[$alIndex]['bonus']."</td>";
                  echo "</tr>";
                }
              }
            }
          ?>
        </table>

        <?php
          // 若沒有任何獎號，則不顯示對獎的按鈕
          if($count>0){
            echo "<div class='btnBar'>";
            echo "  <a class='btn2' href='api/award_number.php?y=$y&p=$p'>全部對獎</a>";
            echo "</div> ";
            $trDisplay="table-row";
          }else{
            echo "<p class='tip2'>尚無資料!</p>";
            $trDisplay="none";
          }
        ?>
        <style>
          .tr{
            display: <?=$trDisplay;?>;
          }
        </style>

      </div>

    </div>
  </div>

  <script src="plugins/jquery-3.5.1.min.js"></script>
  <script>
    $(".topBar .btnDesc").click(function(){
      $(".listBox .overlay").fadeTo(500, 1);
      $(".listBox .descBox").fadeTo(500, 1);
    });
    $(".descBox .btnClose").click(function(){
      $(".listBox .overlay").fadeOut(100);
      $(".listBox .descBox").fadeOut(100);
    });
    $(".overlay").click(function(){
      $(".listBox .overlay").fadeOut(100);
      $(".listBox .descBox").fadeOut(100);
    });
  </script>
</body>
</html>
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

  // 讀取表格資料
  if($p==0){
    // 搜尋該年度全部資料
    // $rows=all("invoice",['year'=>$y]," order by `period`,`id` ASC");


    // 分頁
    // 總筆數
    $dbCount=nums("invoice",['year'=>$y]);
    // 一頁幾筆
    $page_num=10;
    // 頁數(無條件進位)
    $pages=ceil($dbCount/$page_num);
    // 現在的頁數
    $page_now=(!empty($_GET['page']))?$_GET['page']:1;
    // 從第幾筆開始
    $page_start=($page_now-1)*$page_num;
    $rows=all("invoice",['year'=>$y],"  order by `period`,`id` ASC limit $page_start,$page_num");


  }else{
    // 搜尋該年度當期資料
    // $rows=all("invoice",['year'=>$y,"period"=>$p]," order by `period`,`id` ASC");


    // 分頁
    // 總筆數
    $dbCount=nums("invoice",['year'=>$y,"period"=>$p]);
    // 一頁幾筆
    $page_num=10;
    // 頁數(無條件進位)
    $pages=ceil($dbCount/$page_num);
    // 現在的頁數
    $page_now=(!empty($_GET['page']))?$_GET['page']:1;
    // 從第幾筆開始
    $page_start=($page_now-1)*$page_num;
    $rows=all("invoice",['year'=>$y,"period"=>$p],"  order by `period`,`id` ASC limit $page_start,$page_num");
  }



  // 編輯狀態
  $id="";
  $status="";
  $cssDisplay="inline-block";
  $cssScroll="auto";
  if(isset($_GET['id'])){
    $id=$_GET['id'];
  }
  if(isset($_GET['status'])){
    $status=$_GET['status'];
    if($status=="edit"){
      $cssDisplay="none";
    }
    if($status=="del"){
      $cssScroll="hidden";
    }
  }

  // 提示訊息
  $edit="";
  $del="";
  if(isset($_GET['edit'])){
    $edit=$_GET['edit'];
    $tipDisplay="block";
    $boxDisplay="none";
  }
  if(isset($_GET['del'])){
    $del=$_GET['del'];
    $tipDisplay="block";
    $boxDisplay="none";
  }
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>統一發票管理系統</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/list.css">
  <style>
    /* 當有項目在編輯時，將其他編輯與刪除按鈕隱藏 */
    a.btnEdit,
    a.btnDel{
      display: <?=$cssDisplay;?>;
    }
    /* 編輯與刪除的提示訊息 */
    .tipBox{
      display: <?=$tipDisplay;?>;
    }
    .invoiceBox{
      display: <?=$boxDisplay;?>;
    }
    /* 刪除視窗出現時則不可滾動 */
    body{
      overflow-y: <?=$cssScroll;?>;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php 
      $pageheader="發票列表";
      $navPage="2";
      include "include/header.php"; 
    ?>

    <div div class="tipBox">
      <?php
        // 資料編輯
        if(isset($_GET['edit'])){
          if($edit=="true"){
            echo "<h3>資料編輯成功!</h3>";
          }else{
            echo "<h3>資料編輯失敗!</h3>";
          }
          echo "<a class='btn2' href='list.php?y=$y&p=$p'>回到列表</a>";
        }
        // 資料刪除
        if(isset($_GET['del'])){
          if($del=="true"){
            echo "<h3>資料刪除成功!</h3>";
          }else{
            echo "<h3>資料刪除失敗!</h3>";
          }
          echo "<a class='btn2' href='list.php?y=$y&p=$p'>回到列表</a>";
        }
      ?>
    </div>


    <div class="invoiceBox">

      <div class="sideNav">
        <select name="year" onchange="location=this.value;">
          <?php
            $yToday = date("Y");
            $y1=$yToday-4;
            for($i=$y1;$i<=$yToday;$i++){
              $selected=($y==$i)?'selected':'';
              echo "<option value='list.php?y=$i&p=$p' ".$selected.">".$i."</option>";
            }
          ?>
        </select>
        <ul>
          <li><a class="<?=($p==0)?'active':'';?>" href="list.php?y=<?=$y;?>&p=0">全部</a></li>
          <li><a class="<?=($p==1)?'active':'';?>" href="list.php?y=<?=$y;?>&p=1">1,2月</a></li>
          <li><a class="<?=($p==2)?'active':'';?>" href="list.php?y=<?=$y;?>&p=2">3,4月</a></li>
          <li><a class="<?=($p==3)?'active':'';?>" href="list.php?y=<?=$y;?>&p=3">5,6月</a></li>
          <li><a class="<?=($p==4)?'active':'';?>" href="list.php?y=<?=$y;?>&p=4">7,8月</a></li>
          <li><a class="<?=($p==5)?'active':'';?>" href="list.php?y=<?=$y;?>&p=5">9,10月</a></li>
          <li><a class="<?=($p==6)?'active':'';?>" href="list.php?y=<?=$y;?>&p=6">11,12月</a></li>
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
          <p>共有<?php echo $dbCount;?>筆</p>
        </div>

        <form action="api/update_invoice.php" method="post">
          <table class="listTable">
            <tr>
              <td>年份</td>
              <td>期別</td>
              <td>獎號</td>
              <td>花費</td>
              <td></td>
            </tr>
            <?php
              foreach($rows as $row){
                
                if($row['id']==$id && $status=="edit"){
                  // 編輯
                  echo "<tr class='item".$row['id']." itemEdit'>";
                  echo "  <td>";
                  echo "    <select name='year'>";
                              for($i=$y1;$i<=$yToday;$i++){
                                $selected=($y==$i)?'selected':'';
                                echo "<option value='$i' ".$selected.">".$i."</option>";
                              }
                  echo "    </select>";
                  echo "  </td>";
                  echo "  <td>";
                  echo "    <select name='period'>";
                              for($i=1;$i<=6;$i++){
                                $selected=($p==$i)?'selected':'';
                                echo "<option value='$i' ".$selected.">".(2*$i-1)."、".(2*$i)."月</option>";
                              }
                  echo "    </select>";
                  echo "  </td>";
                  echo "  <td>";
                  echo "    <div class='divNumber'>";
                  echo "      <input type='text' name='code' placeholder='英文2碼' maxlength='2' value='".$row['code']."' required>";
                  echo "      <input type='number' name='number' placeholder='數字8碼' value='".$row['number']."' onkeyup='strlen(this,8);' required>";
                  echo "    </div>";
                  echo "  </td>";
                  echo "  <td>";
                  echo "    <input type='number' name='expend' value='".$row['expend']."' required>";
                  echo "    <input type='hidden' name='id' value='".$row['id']."'>";
                  echo "    <input type='hidden' name='y' value='$y'>";
                  echo "    <input type='hidden' name='p' value='$p'>";
                  echo "  </td>";
                  echo "  <td>";
                  echo "    <input class='btn btnSave' type='submit' value='儲存'>";
                  echo "    <a class='btn btnCancel' href='list.php?y=$y&p=$p'>取消</a>";
                  echo "  </td>";
                  echo "</tr>";
                }else{
                  // 顯示
                  echo "<tr class='item".$row['id']."'>";
                  echo "  <td>".$row['year'] ."</td>";
                  echo "  <td>".($row['period']*2-1).",".($row['period']*2)."月</td>";
                  echo "  <td>".$row['code'].$row['number']."</td>";
                  echo "  <td>".$row['expend'] ."</td>";
                  echo "  <td>";
                  echo "    <a class='btn btnEdit' href='list.php?y=$y&p=$p&id=".$row['id']."&status=edit'>編輯</a>";
                  echo "    <a class='btn btnDel' href='list.php?y=$y&p=$p&id=".$row['id']."&status=del'>刪除</a>";
                  echo "  </td>";
                  echo "</tr>";
                }
              }
            ?>
          </table>
        
          <?php
            if($dbCount==0){
              echo "<p class='tip'>尚無資料!</p>";
            }
          ?>
        </form>

        <?php
          if($status=="del"){
            echo "<div class='overlay'></div>";
            echo "<div class='checkBox'>";
            echo "  <form action='api/del_invoice.php' method='post'>";
            echo "    <p>確認刪除該筆資料?</p>";
            echo "    <input type='hidden' name='id' value='$id'>";
            echo "    <input type='hidden' name='y' value='$y'>";
            echo "    <input type='hidden' name='p' value='$p'>";
            echo "    <input class='btn2 btnOK' type='submit' value='是'>";
            echo "    <a class='btn2 btnClose' href='list.php?y=$y&p=$p'>否</a>";
            echo "  </form>";
            echo "</div>";
          }
        ?>

        
        <!-- 分頁 -->
        <div class="pageNav">
          <ul>
            <?php
              if($page_now>1){

                if($p==0){
                  // 整年度搜尋
                  echo "<li><a href='?y=$y&p=0&page=".($page_now-1)."'><</a></li>";
                }else{
                  // 搜尋單一期別
                  echo "<li><a href='?y=$y&p=$p&page=".($page_now-1)."'><</a></li>"; 
                }
              }


              for($i=1;$i<=$pages;$i++){
                $active=($i==$page_now)?"class='active'":"";
                if($p==0){
                  echo "<li><a $active href='?y=$y&p=0&page=$i'>$i</a></li>";
                }else{
                  echo "<li><a $active href='?y=$y&p=$p&page=$i'>$i</a></li>";
                }
              }

              if($page_now<$pages){  
                if($p==0){
                  // 整年度搜尋
                  echo "<li><a href='?y=$y&p=0&page=".($page_now+1)."'>></a></li>";
                }else{
                  // 搜尋單一期別
                  echo "<li><a href='?y=$y&p=$p&page=".($page_now+1)."'>></a></li>";
                }
              }  
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script src="plugins/jquery-3.5.1.min.js"></script>
  <script src="js/js.js"></script>
</body>
</html>
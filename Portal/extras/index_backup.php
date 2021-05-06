  <!DOCTYPE html>
  <html>
  <head>
  <style>
  @font-face {
    font-family: DaggerSquare;
    src: url("fonts/podium-font.woff") format("woff"), url("fonts/podium-font.ttf") format("truetype");
  }

  #podium-box {
    margin: 0 auto;
    display: flex;
  }

  .podium-number {
    font-family: DaggerSquare;
    font-weight: bold;
    font-size: 2em;
    color: black;
  }

  .step-container {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .step-container>div:first-child {
    margin-top: auto;
    text-align: center;
  }

  .step {
    text-align: center;
  }

  .bg-blue {
    /*background-color: #00a8ff;*/
  /*background-image: rgba(93,84,240,0.5);*/
  background-image: linear-gradient(to bottom, rgba(255,0,0,0), rgba(255,0,0,1));
  /*background-color: -webkit-linear-gradient(left, rgba(0,168,255,0.5), rgba(185,0,255,0.5));*/
  /*background-color: -o-linear-gradient(left, rgba(0,168,255,0.5), rgba(185,0,255,0.5));*/
  /*background-color: -moz-linear-gradient(left, rgba(0,168,255,0.5), rgba(185,0,255,0.5));*/
  /*background-color: linear-gradient(left, rgba(0,168,255,0.5), rgba(185,0,255,0.5));*/
  }

  #first-step {
    height: 50%;
    background-image: linear-gradient(to bottom right, rgba(218,165,32,0), rgba(218,165,32,1));
  }

  #second-step {
    height: 35%;
    background-image: linear-gradient(to bottom right, rgba(192,192,192,0), rgba(192,192,192,1));
  }

  #third-step {
    background-image: linear-gradient(to bottom right, rgba(205, 127, 50,0), rgba(205, 127, 50,1));
    height: 30%;
  }

  
  </style>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link rel="import" type="css" href="podium-chart.css">
  </head>
  <body>

  <?php
  // $q = intval($_GET['q']);
require_once "config.php";
  $sql="SELECT  * from  records ORDER BY weight DESC"; 
$result=mysqli_query($link,$sql); 
$resultset=array(); 
// Associative array 
while($row=mysqli_fetch_assoc($result)) 
{ 
  $resultset[]=$row; 
} 

?>
<div id="podium-box" class="row justify-content-center" style="height: 300px">
  <!-- <div class="col-md-1"></div> -->

    <div class="col-md-3 step-container m-0 p-0">
      <div>
        <?php
        echo '<img class="rounded-circle" src="data:image/jpeg;base64,'.base64_encode($resultset[1]['image'] ).'" height="150" width="150"/>'?>
      </div>
      <div id="second-step" class="bg-blue step centerBoth podium-number">
        <?php echo $resultset[1]['name']; ?>
      </div>
    </div>
    <div class="col-md-3 step-container m-0 p-0">
      <div>
      <?php
        echo '<img class="rounded-circle" src="data:image/jpeg;base64,'.base64_encode($resultset[0]['image'] ).'" height="150" width="150"/>'?>
      </div>
      <div id="first-step" class="step centerBoth podium-number">
        <?php echo $resultset[0]['name']; ?>
      </div>
    </div>
    <div class="col-md-3 step-container m-0 p-0">
      <div>
        <?php
        echo '<img class="rounded-circle" src="data:image/jpeg;base64,'.base64_encode($resultset[2]['image'] ).'" height="150" width="150"/>'?>
      </div>
      <div id="third-step" class="bg-blue step centerBoth podium-number">
        <?php echo $resultset[2]['name']; ?>
      </div>
    </div>
  </div>
  <?php

  // mysqli_select_db($con,"ajax_demo");
  $sql="SELECT * FROM records ORDER BY weight DESC ";
  $result = mysqli_query($link,$sql);

  echo "<table class='table table-active table-striped table-hover'>
  <thead class='table-dark'>
  <tr>
  <th>EMP ID</th>
  <th>PHOTO</th>
  <th>NAME</th>
  <th>SCORE</th>
  </tr>
   </thead>"  ;
  
  while($row = mysqli_fetch_assoc($result)  ) {


  ?>
<!-- <img src="data:image/jpg;charset-utf8;base64,<?php echo base64_encode($row['image']) ?>"/> -->
    <tr>
      <td><?php echo $row['empid']; ?></td>
      <td align="center"><?php echo '<img class="rounded-circle" src="data:image/jpeg;base64,'.base64_encode($row['image'] ).'" height="75" width="75" align="left"/>'?></td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['weight']; ?></td>
    </tr> 


  <?php

}
  echo "</table>";
  mysqli_close($link);
  ?>


  </body>
  </html>
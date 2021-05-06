  <!DOCTYPE html>
  <html>
  <head>
    <title>Incentivised Tech Bin</title>
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
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

.bg-image {
  /*background-image: url("images/bg2.jpg"); The image used */
  background-color: #f5f5f5; /* Used if the image is unavailable */
  /*height: 500px;  You must set a specified height */
  /*background-position: center; /* Center the image */*/
   /* Do not repeat the image */
  background-size: cover; /* Resize the background image to cover the entire container */
}
  
  </style>

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
 


  <link rel="import" type="css" href="podium-chart.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://use.fontawesome.com/7e620112a0.js"></script>

  </head>
  

<!-- ========================================================================================================= -->

  <body class="bg-image">
<div class="row justify-content-center pt-10">
<div id="custom-bootstrap-menu" class="navbar navbar-default col-md-10">
    <div class="container-fluid h-10">
        <div class="navbar-header"><a class="navbar-brand mb-0 h1 text-light" href="#">Incentivised Tech Bin</a>
        </div>
         <div class="navbar-right">

          <a class="nav-item mb-0 h6" href="login.php"><i class="fa fa-sign-in" style="color:#f7f7f7"></i><span class="sr-only">(current)</span></a>
        </div>
    </div>
</div>
</div>
<!-- Navbar -->

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
  $result = mysqli_query($link,$sql); ?>

<div class="row justify-content-center pt-10">
  <div class="col-md-9 ">
  <?php

  echo "<table class='table table-active table-striped table-dark table-borderless table-hover'>
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
  </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  </body>
  </html>
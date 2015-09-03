<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <title>3Pont</title>
        
        <base href="<?= base_url()?>"/>
        <link href="resources/css/jquery.mobile.structure-1.3.2.min.css" rel="stylesheet" type="text/css"/>
        <link href="resources/css/normalize.css" rel="stylesheet" type="text/css"/>
        <link href="resources/css/foundation.css" rel="stylesheet" type="text/css"/>
        <link href="resources/css/custom.css" rel="stylesheet" type="text/css"/>
        


</head>
<body>

<div data-role="page" id="news" class="main">
    <div data-role="header">
    
        <div class="off-canvas-wrap">
  <div class="inner-wrap">
    <nav class="tab-bar">
      <section class="left-small"> <a href="#" class="back-icon"><span class="back">Back</span></a> </section>
      <section class="middle tab-bar-section">
        <span class="ui-title"><img src="resources/img/logo.jpg"></span>
      </section>
      <section class="right-small"> <a class="right-off-canvas-toggle menu-icon" ><span></span></a> </section>
    </nav>
    
    <aside class="right-off-canvas-menu">
      <ul class="off-canvas-list">
        <li>
          <label>Users</label>
        </li>
        <li><a href="#">Menu one</a></li>
        ...
      </ul>
    </aside>
    <section class="sticky-top">News</section>
    <section class="main-section"> 
    
        <?php
        foreach ($news as $key) {
           ?>
           <div class="wrapper">
            <a href="#news-details" data-transition="slide">
               <div class="ribbon-wrapper-green">
                  <div class="ribbon-green">Hihi2</div>
                  </div>
                  <div class="news-img"><img src="<?= $key['image']?>"></div>
                  <div class="news-container">
                      <div class="date"><?= $key['date']?></div>
                      <div class="news-content"><h3><?= $key['title']?></h3>
                      <?= $key['desc']?>
                      </div>
                      
                  </div>
              </a>
        </div><!--end of news-->
           <?php 
        }
        ?>
      
        
    </section>
    
    <a class="exit-off-canvas"></a> </div>
    
</div>
       
    </div>
    
    
</div>





<div data-role="page" id="news-details" class="main">
    <div data-role="header">
    
        <div class="off-canvas-wrap">
  <div class="inner-wrap">
    <nav class="tab-bar">
      <section class="left-small"> <a href="#news" data-transition="slide" class="back-icon"><span class="back">Back</span></a> </section>
      <section class="middle tab-bar-section">
        <span class="ui-title"><img src="resources/img/logo.jpg"></span>
      </section>
      <section class="right-small"> <a class="right-off-canvas-toggle menu-icon" ><span></span></a> </section>
    </nav>
    
    <aside class="right-off-canvas-menu">
      <ul class="off-canvas-list">
        <li>
          <label>Users</label>
        </li>
        <li><a href="#">Menu one</a></li>
        ...
      </ul>
    </aside>
    <section class="sticky-top">News-details</section>
    <section class="main-section">
   
        
    </section>
    
    <a class="exit-off-canvas"></a> </div>
    
</div>
       
    </div>
    
    
</div>

<script src="resources/js/jquery-2.0.2.min.js" ></script>
<script src="resources/js/jquery.mobile-1.3.2.min.js" ></script>
<script src="resources/js/foundation.min.js" ></script>
<script src="resources/js/custom.js" ></script>
<script >
      $(document).foundation();
    </script>
</body>

</html>
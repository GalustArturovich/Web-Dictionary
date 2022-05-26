<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Web Dictionary</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&amp;subset=cyrillic" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">

</head>
<body>
  <form action="uploadcsv.php" method="post" enctype="multipart/form-data">
   <div class="field__wrapper">
     <label class="field__file-wrapper" for="field__file-2">
      <input type="file" name="file" id="file" class="field field__file" multiple>
      <div class="field__file-button">Добавить словарь</div>
      <!-- <div class="field__file-fake">Файл не выбран</div> -->

    </label>
  </div>

  <button type="button" class="field__file-button" style="border:none; margin: 10px auto" data-toggle="modal" data-target="#exampleModal">
   Загрузить словарь
 </button>

 <div class="modal fade" style="padding: 0;" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Введите название словаря</h2>
      </div>
      <div class="modal-body">
       <form action="#" align="center" method="POST" enctype="multipart/form-data" >
         <div class="form-group" >
          <input type="text" name="name" class="form-control __required name" id="name" id="formGroupExampleInput" placeholder="Словарь" required><br>
        </div>
        <div class="field__wrapper" style="margin-top: 10px;">
         <label class="field__file-wrapper" for="field__file-2">
          <input type="submit" name="submit" class="field field__file" style="    width: 180px;
          height: 45px;">
          <div class="field__file-button">Загрузить словарь</div>
        </label>
      </div>
    </div>
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
</div>
</div>
</div>
</div>

</form>

<?php
include 'config.php';
$name = $dbh->query("SELECT * FROM `name_fjalor`");
$translate = $dbh->query("SELECT * FROM `translate`");
$name_row = $name->fetch();  
if ($name->rowCount() != 0){
  ?>

  <h5 class="lg" style="text-align: center; margin: 30px 0 !important; padding: 5px 0; background: #f2f2ff; border-radius: 0 !important;">Текущий словарь: <?=$name_row['name_fjalor'].' ('.$translate->rowCount().' слов)'?> </h5>
  <?php 

}

$translate_row = $translate->fetch(); 
?>

<h2  data-category="<?=$name_row['id']?>" align="center" style="margin: 50px;"><?=$translate_row['ENG']; ?></h2>
<div align="center" id="cl1"><h2 align="center" style="margin: 30px;">????</h2></div>
<div align="center" id="cl2" style="display:none"><h2 align="center" style="margin: 30px;"><?=$translate_row['RUS']; ?></h2></div>

<form id="ajax-form" method="post">
  <div style="display: flex; margin: 0 auto; width: 500px;">


    <button class="field__file-button" style="width: 178px !important;
    border:none; margin: 10px auto" onclick="(document.getElementById('cl1').style.display=='none') ? console.log('Определённый контент уже скрыт') : document.getElementById('cl1').style.display='none'; document.getElementById('cl2').style.display=''">Показать перевод</button>
    <button type="button" class="field__file-button" style="width: 178px !important; border:none; margin: 10px auto">
      Следующее слово
    </button>



  </div>

</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>
<script>
  $('#ajax-form').submit(function(e) {
    e.preventDefault();
  });
</script>
</body>
</html>
<?php /* Smarty version 2.6.25, created on 2010-11-21 21:29:52
         compiled from index.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
  <title><?php echo $this->_tpl_vars['Title']; ?>
</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Language" content="ru" />
  <meta name="description" content="Simpla" />
  <meta name="keywords" content="" />
  <meta name="robots" content="all" />
  <link rel="stylesheet" type="text/css" href="simpla.css" media="screen" />	
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

  <script type="text/javascript" src="js/jquery/jquery.js"></script>
  <script type="text/javascript" src="js/thickbox/thickbox.js"></script>
  <link rel="stylesheet" href="js/thickbox/thickbox.css" type="text/css" media="screen" />
  
  <script>
    var theme = "<?php echo $this->_tpl_vars['Settings']->theme; ?>
";
  </script>
</head>
<body>

<a href='../' class="bookmark"><img  title='Перейти на сайт' alt='Перейти на сайт' border=0 src='images/bookmark.gif'></a>
<div id="page">
	<!-- Icons #Begin /-->
	<div id="icon">
		<table id="table">
			<tr>
				<td><a href="index.php"><img  src="./images/icon_main.jpg" alt="Главная" />Главная</a></td>
				<td><a href="index.php?section=Sections"><img src="./images/icon_content.jpg" alt="Cтраницы" />Cтраницы</a></td>
				<td><a href="index.php?section=Storefront"><img src="./images/icon_products.jpg" alt="Товары" />Товары</a></td>
				<td><a href="index.php?section=Orders"><img src="./images/icon_orders.jpg" alt="Заказы" />Заказы</a></td>
				<td><a href="index.php?section=Users"><img src="./images/icon_users.jpg" alt="Покупатели" />Покупатели</a></td>
				<td><a href="index.php?section=Comments"><img src="./images/icon_comments.jpg" alt="Комментарии" />Комментарии</a></td>
				<td><a href="index.php?section=Import"><img src="./images/icon_auto.jpg" alt="Автоматизация" />Автоматизация</a></td>
				<td><a href="index.php?section=Themes"><img src="./images/icon_design.jpg" alt="Дизайн" />Дизайн</a></td>
				<td><a href="index.php?section=Setup"><img src="./images/icon_setup.jpg" alt="Настройки"/>Настройки</a></td>
      </tr>
    </table>
    <!-- Icons #End /-->

    <?php echo $this->_tpl_vars['Body']; ?>
	

    <!-- Footer #Begin /-->
    <div id="footer">
      <div id="footer_right">
        <img src="./images/license.jpg" alt="" class="fl"/><a href="license.html" class="fl">Условия использования</a>
        <img src="./images/logout.jpg" alt="" class="flx"/><a href="index.php?action=logout" class="fl">Выход</a>
      </div>
    </div>
    <!-- Footer #End /-->
      
   
    
  </div>
</body>
</html>
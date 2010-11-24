<?PHP

require_once('Widget.admin.php');

// Этот класс выбирает модуль в зависимости от параметра Section и выводит его на экран
class Page extends Widget
{
    // Модуль (пока неизвестно какой)
	var $module;
    var $allowed_modules = array("MainPage", "Sections", "Section",
                                 "NewsLine", "NewsItem", "Articles", "Article",
                                 "Storefront", "Product", "Categories", "Category", "Brands", "Brand",
                                 "Comments", "Feedback",
                                 "Orders", "Order", "Users", "User", "Groups", "Group",
                                 "Import", "Export",
                                 "Themes", "Templates", "Styles", "Images",
                                 "Backup", "Setup", "Currency", "DeliveryMethods", "DeliveryMethod", "PaymentMethods", "PaymentMethod",
                                 "Properties", "Property", "SmsNotify" );

	// Конструктор
	function Page(&$parent)
	{
	    // Вызываем конструктор базового класса
		parent::Widget($parent);

		$this->add_param('section');

		// Берем название модуля из get-запроса
		$section = $this->param('section');

		// Если запросили недопустимый модуль - используем модуль MainPage
		if(empty($section) || !in_array($section, $this->allowed_modules))
		  $section = 'MainPage';

		// Подключаем файл с необходимым модулем
		require_once($section.'.admin.php');

		// Создаем соответствующий модуль
		if(class_exists($section))
			$this->module = new $section($this);
	}

	function fetch()
	{
		$this->module->fetch();
		$this->smarty->assign("Title", $this->module->title);
		$this->smarty->assign("Keywords", $this->module->keywords);
		$this->smarty->assign("Description", $this->module->description);
		$this->smarty->assign("Body", $this->module->body);

		$this->body = $this->smarty->fetch('index.tpl');
	}
}
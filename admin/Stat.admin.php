<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Setup displays news
############################################
class Stat extends Widget
{
  function Stat(&$parent)
  {
	parent::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
     if(isset($_POST['age']))
     {
       $age = $_POST['age'];
  	   $query = "DELETE FROM stat WHERE date<=DATE_SUB(CURDATE(), INTERVAL $age DAY)";
  	   $this->db->query($query);

     }
  }

  function fetch()
  {

    $this->title = $this->lang->STATISTICS;

  	if(isset($_GET['category_id']))
  	{
  		$category_id = $_GET['category_id'];
  		$query = "SELECT * FROM categories WHERE category_id='$category_id'";
  		$this->db->query($query);
  		$category = $this->db->result();


 		### All products
 		$query = "SELECT products.*, brands.name as brand FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id WHERE products.category_id='$category_id' ORDER BY brand, products.model";
 		$this->db->query($query);
 		$ps = $this->db->results();
 		$products=array();
 		foreach($ps as $p)
 		  $products[$p->product_id] = $p;
  		$this->smarty->assign('Products', $products);

  		### Today
 		$query = "SELECT products.*, brands.name as brand, SUM(stat.hits) as hits FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id LEFT JOIN stat ON stat.product_id = products.product_id WHERE date=CURDATE() AND products.category_id='$category_id' GROUP BY products.product_id, products.model ORDER BY brand, products.model";
 		$this->db->query($query);
 		$tps = $this->db->results();
 		$today_products = array();
 		foreach($tps as $tp)
 		  $today_products[$tp->product_id] = $tp;
  		$this->smarty->assign('TodayProducts', $today_products);

 		### Yesterday
 		$query = "SELECT products.*, brands.name as brand, SUM(stat.hits) as hits FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id LEFT JOIN stat ON stat.product_id = products.product_id WHERE date=DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND products.category_id='$category_id' GROUP BY products.product_id, products.model ORDER BY  brand, products.model";
 		$this->db->query($query);
 		$yps = $this->db->results();
 		$yesterday_products = array();
 		foreach($yps as $yp)
 		  $yesterday_products[$yp->product_id] = $yp;
  		$this->smarty->assign('YesterdayProducts', $yesterday_products);

 		### Week
 		$query = "SELECT products.*, brands.name as brand, SUM(stat.hits) as hits FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id LEFT JOIN stat ON stat.product_id = products.product_id WHERE date>=DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND date<=CURDATE() AND products.category_id='$category_id' GROUP BY products.product_id, products.model ORDER BY brand, products.model";
 		$this->db->query($query);
 		$wps = $this->db->results();
 		$week_products = array();
 		foreach($wps as $wp)
 		  $week_products[$wp->product_id] = $wp;
  		$this->smarty->assign('WeekProducts', $week_products);

 		### Month
 		$query = "SELECT products.*, brands.name as brand, SUM(stat.hits) as hits FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id LEFT JOIN stat ON stat.product_id = products.product_id WHERE date>=DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND date<=CURDATE() AND products.category_id='$category_id' GROUP BY products.product_id, products.model ORDER BY brand, products.model";
 		$this->db->query($query);
 		$mps = $this->db->results();
 		$month_products = array();
 		foreach($mps as $mp)
 		  $month_products[$mp->product_id] = $mp;
  		$this->smarty->assign('MonthProducts', $month_products);

 		### Year
 		$query = "SELECT products.*, brands.name as brand, SUM(stat.hits) as hits FROM products LEFT JOIN brands ON products.brand_id=brands.brand_id LEFT JOIN stat ON stat.product_id = products.product_id WHERE date>=DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND date<=CURDATE() AND products.category_id='$category_id' GROUP BY products.product_id, products.model ORDER BY brand, products.model";
 		$this->db->query($query);
 		$rps = $this->db->results();
 		$year_products = array();
 		foreach($rps as $rp)
 		  $year_products[$rp->product_id] = $rp;
  		$this->smarty->assign('YearProducts', $year_products);

  		$this->smarty->assign('Category', $category);
  		$this->smarty->assign('ErrorMSG', $this->error_msg);
     	$this->smarty->assign('Lang', $this->lang);
 		$this->body = $this->smarty->fetch('stat_products.tpl');

 	}else
 	{
 		### All categories
 		$query = "SELECT * FROM categories  ORDER BY categories.name";
 		$this->db->query($query);
 		$categories = $this->db->results();
  		$this->smarty->assign('Categories', $categories);

 		### Today
 		$query = "SELECT *, SUM(stat.hits) as hits FROM stat, categories LEFT JOIN   products  ON categories.category_id = products.category_id WHERE stat.product_id = products.product_id AND date=CURDATE() GROUP BY categories.category_id ORDER BY categories.name";
 		$this->db->query($query);
 		$tcs = $this->db->results();
 		$today_categories = array();
 		foreach($tcs as $tc)
 		  $today_categories[$tc->category_id] = $tc;
  		$this->smarty->assign('TodayCategories', $today_categories);

 		### Yesterday
 		$query = "SELECT *, SUM(stat.hits) as hits FROM stat, categories LEFT JOIN products  ON categories.category_id = products.category_id WHERE stat.product_id = products.product_id AND date=DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY categories.category_id ORDER BY categories.name";
 		$this->db->query($query);
 		$ycs = $this->db->results();
 		$yesterday_categories = array();
 		foreach($ycs as $yc)
 		  $yesterday_categories[$yc->category_id] = $yc;
  		$this->smarty->assign('YesterdayCategories', $yesterday_categories);

 		### Week
 		$query = "SELECT *, SUM(stat.hits) as hits FROM stat, categories LEFT JOIN  products ON categories.category_id = products.category_id WHERE stat.product_id = products.product_id AND date>=DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND date<=CURDATE() GROUP BY categories.category_id ORDER BY categories.name";
 		$this->db->query($query);
 		$wcs = $this->db->results();
 		$week_categories = array();
 		foreach($wcs as $wc)
 		  $week_categories[$wc->category_id] = $wc;
  		$this->smarty->assign('WeekCategories', $week_categories);

 		### Month
 		$query = "SELECT *, SUM(stat.hits) as hits FROM stat, categories LEFT JOIN  products  ON categories.category_id = products.category_id WHERE stat.product_id = products.product_id AND date>=DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND date<=CURDATE() GROUP BY categories.category_id ORDER BY categories.name";
 		$this->db->query($query);
 		$mcs = $this->db->results();
 		$month_categories = array();
 		foreach($mcs as $mc)
 		  $month_categories[$mc->category_id] = $mc;
  		$this->smarty->assign('MonthCategories', $month_categories);

 		### Year
 		$query = "SELECT *, SUM(stat.hits) as hits FROM stat, categories LEFT JOIN products  ON categories.category_id = products.category_id WHERE stat.product_id = products.product_id AND date>=DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND date<=CURDATE() GROUP BY categories.category_id ORDER BY categories.name";
 		$this->db->query($query);
 		$rcs = $this->db->results();
 		$year_categories = array();
 		foreach($rcs as $rc)
 		  $year_categories[$rc->category_id] = $rc;
  		$this->smarty->assign('YearCategories', $year_categories);

     	$this->smarty->assign('Lang', $this->lang);
  		$this->smarty->assign('ErrorMSG', $this->error_msg);
 		$this->body = $this->smarty->fetch('stat_categories.tpl');
  	}

  }


}
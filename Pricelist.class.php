<?PHP

require_once('Widget.class.php');
require_once('Storefront.class.php');

############################################
# Class Pricelist
############################################
class Pricelist extends Widget
{
  var $single = true;
  function Pricelist(&$parent)
  {
    parent::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
  }

  function fetch()
  {
		// Е-каталог отстал от жизни и не понимает utf-8, ему cp1251 подавай
		// И для екселя тоже 1251
		$charset = 'cp1251';
		setlocale ( LC_ALL, 'ru_RU.'.$charset);
		$this->db->query('SET NAMES '.$charset);
		// Выбираем из базы настроки, которые задаются в разделе Настройки в панели управления
		$query = 'SELECT name, value FROM settings';
		$this->db->query($query);
		$sts = $this->db->results();
		foreach($sts as $s)
		{	
			$name = $s->name;
			$settings->$name = $s->value;
		}
  
  
      	$format = $_GET['format'];
    	switch($format)
    	{
    		case('yandex'):
    		{
				$query = "SELECT products.*, brands.name as brand, categories.single_name as category,
				products_variants.name as variant_name, products_variants.price as price, products_variants.variant_id as variant_id
                FROM products_variants, products LEFT JOIN categories ON categories.category_id = products.category_id LEFT JOIN brands ON products.brand_id = brands.brand_id
                WHERE categories.enabled=1
                and products.enabled=1
                and products_variants.stock>0
                and products_variants.price>0
                and products_variants.product_id=products.product_id
                ORDER BY categories.order_num, products.order_num, products_variants.position DESC";    	
                $this->db->query($query);	
    		    $products = $this->db->results();
    			$query = 'SELECT * FROM categories WHERE categories.enabled ORDER BY categories.order_num';
    			$this->db->query($query);
    			$categories = $this->db->results();
    			$query = 'SELECT * FROM currencies ORDER BY code="RUR" DESC, main DESC, currency_id';
    			$this->db->query($query);
    			$currencies = $this->db->results();
    			
    			if($currencies[0]->code == 'RUR')
    			{
    				$rur_rate = $currencies[0]->rate_from/$currencies[0]->rate_to;
    				array_shift($currencies);
    				$cbrf = false;
    			}
    			else
    			{
    				$rur_rate = 1;
    				$cbrf = true;
    			}

				$ctype="text/xml";
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-Type: $ctype");
				header('Content-disposition: attachment; filename="yandex.xml"');
				header("Content-Transfer-Encoding: binary");
				print "<?xml version='1.0' encoding='cp1251'?>"."\n";
				print "<!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>"."\n";
				print "<yml_catalog date='".date('Y-m-d H:m')."'>"."\n";
				print "<shop>"."\n";
				print "<name>".$settings->site_name."</name>"."\n";
				print "<company>".$settings->company_name."</company>"."\n";
				print "<url>http://".$this->root_url."</url>"."\n";

				// Currencies
				print "<currencies>"."\n";
				print "\t<currency id='RUR' rate='1'/>"."\n";
				foreach($currencies as $currency)
				{
					if($cbrf)
						print "\t<currency id='".$currency->code."' rate='CBRF'/>"."\n";
					else
						print "\t<currency id='".$currency->code."' rate='".round($currency->rate_to/$currency->rate_from*$rur_rate, 3)."'/>"."\n";
				}	
				print "</currencies>"."\n";
				
				// Categories
				print "<categories>"."\n";
				foreach($categories as $category)
				{
					if($category->parent>0)					 
						print "\t<category id='".$category->category_id."' parentId='".$category->parent."'>".htmlspecialchars($category->name, ENT_QUOTES)."</category>"."\n";
					else
						print "\t<category id='".$category->category_id."'>".htmlspecialchars($category->name, ENT_QUOTES)."</category>"."\n";
				}	
				print "</categories>"."\n";
				
				// Offers
				print "<offers>"."\n";
				foreach($products as $product)
				{
					if($product->brand)
						print "<offer id='".$product->variant_id."' type='vendor.model' available='true'>"."\n";
					else
						print "<offer id='".$product->variant_id."' available='true'>"."\n";

					print "<url>http://".$this->root_url.'/products/'.$product->url."</url>"."\n";
					print "<price>".$product->price."</price>"."\n";
					print "<currencyId>".$this->main_currency->code."</currencyId>"."\n";
					print "<categoryId>".$product->category_id."</categoryId>"."\n";
					if($product->large_image)
						print "<picture>http://$this->root_url/files/products/".urlencode($product->large_image)."</picture>"."\n";
					if($product->brand)
					{
						if($product->category)
							print "<typePrefix>".htmlspecialchars($product->category, ENT_QUOTES)."</typePrefix>"."\n";
						print "<vendor>".htmlspecialchars($product->brand)."</vendor>"."\n";
						print "<model>".htmlspecialchars($product->model, ENT_QUOTES)." ".htmlspecialchars($product->variant_name, ENT_QUOTES)."</model>"."\n";
					}else
					{
						print "<name>".htmlspecialchars($product->model, ENT_QUOTES)." ".htmlspecialchars($product->variant_name, ENT_QUOTES)."</name>"."\n";
					}
					print "<description>".htmlspecialchars(strip_tags($product->description), ENT_QUOTES)."</description>"."\n";
					print "</offer>"."\n";
				}	
				print "</offers>"."\n";

				print "</shop>"."\n";
				print "</yml_catalog>"."\n";
								
    			exit();
    		}
    		default:
    		{
				$query = "SELECT products.*, brands.name as brand, categories.single_name as category,
				products_variants.name as variant_name, products_variants.price as price, products_variants.variant_id as variant_id
                FROM products_variants, products LEFT JOIN categories ON categories.category_id = products.category_id LEFT JOIN brands ON products.brand_id = brands.brand_id
                WHERE categories.enabled=1
                and products.enabled=1
                and products_variants.stock>0
                and products_variants.price>0
                and products_variants.product_id=products.product_id
                ORDER BY categories.order_num, products.order_num, products_variants.position DESC";    	
                $this->db->query($query);	
    		    $products = $this->db->results();

    			$query = 'SELECT * FROM currencies ORDER BY  main DESC, currency_id';
    			$this->db->query($query);
    			$currencies = $this->db->results();
    			
				$ctype="application/vnd.ms-excel";
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-Type: $ctype");
				header('Content-disposition: attachment; filename="price.xls"');
				header("Content-Transfer-Encoding: binary");
				$this->print_xls_header();
				print '<body link="blue" vlink="purple">'."\n";
				print '<table x:str border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;table-layout:fixed;">'."\n";
				foreach($products as $product)
				{
					print "<tr>";
					print "<td  class='xl24'>{$product->category}</td>";
					print "<td  class='xl24'>{$product->category_single} {$product->brand} {$product->model} {$product->variant_name}</td>";
					foreach($currencies as $currency)
					{
						print "<td class='xl24'>".round($product->price*$currency->rate_from/$currency->rate_to,2).' '.$currency->sign."</td>";
					}
					print "</tr>"."\n";
				}
				print '</table></body></html>';	
				
				exit();		
    		 
    		}

    	}
    
    }
    
    function print_xls_header()
    {
    
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="Content-Language" content="ru" />
<meta name="ProgId" content="Excel.Sheet">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:LastAuthor>Simpla CMS</o:LastAuthor>
  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
  <o:Version>10.2625</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:DownloadComponents/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<style>
<!--table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\ ";}
@page
	{margin:1.0in .75in 1.0in .75in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;}
tr
	{mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}
.style0
	{mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border:none;
	mso-protection:locked visible;
	mso-style-name:Normal;
	mso-style-id:0;}
td
	{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:none;
	mso-background-source:auto;
	mso-pattern:auto;
	mso-protection:locked visible;
	white-space:nowrap;
	mso-rotate:0;}
.xl24
	{mso-style-parent:style0;
	white-space:normal;}
-->
</style>
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
	<x:Name>{/literal}{$Settings->site_name}{literal}</x:Name>
	<x:WorksheetOptions>
	 <x:Selected/>
	 <x:ProtectContents>False</x:ProtectContents>
	 <x:ProtectObjects>False</x:ProtectObjects>
	 <x:ProtectScenarios>False</x:ProtectScenarios>
	</x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
  <x:WindowHeight>10005</x:WindowHeight>
  <x:WindowWidth>10005</x:WindowWidth>
  <x:WindowTopX>120</x:WindowTopX>
  <x:WindowTopY>135</x:WindowTopY>
  <x:ProtectStructure>False</x:ProtectStructure>
  <x:ProtectWindows>False</x:ProtectWindows>
 </x:ExcelWorkbook>
</xml><![endif]-->
</head>
<?php    


  }
}
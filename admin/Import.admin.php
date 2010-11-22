<?PHP

require_once('Widget.admin.php');
require_once('../placeholder.php');


############################################
# Class Import
############################################
class Import extends Widget
{	
	var $csv_line_maxlength = 20000;	
	var $allowed_extensions = array('csv', 'txt');
	var $subcategory_delimiter = '/';
 
	var $products_added = 0;
	var $products_updated = 0;
	var $variants_added = 0;
	var $variants_updated = 0;

	function Import(&$parent)
	{
		parent::Widget($parent);
	}


	function fetch()
	{
		$this->title = $this->lang->PRODUCTS_IMPORT;
		
		if(isset($_POST['format']) && !empty($_POST['format']) && !empty($_FILES['file']['tmp_name']))
		{
			if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
			{
				header('Location: http://'.$this->root_url.'/admin/');
				exit();
			}
		
			$format = $_POST['format'];
			$fname = $_FILES['file']['tmp_name'];
		  
			if(!in_array(end(explode(".", $_FILES['file']['name'])), $this->allowed_extensions))
			{
				$this->error_msg = 'Неподдерживаемый тип файла';
			}
			else
			{		  
				// Узнаем какая кодировка у файла
				$fh = fopen($fname, 'r');
				$teststring = fread($fh, 2);
				fclose($fh);
		
				// Кодировки
				if (preg_match('//u', $teststring))
				{
					$charset = 'UTF8';
				}else
				{
					$charset = 'CP1251';
				}
		
				setlocale (LC_ALL, 'ru_RU.'.$charset);

				$this->db->query('SET NAMES '.$charset);
				$query = sql_placeholder('UPDATE settings SET value=? WHERE name="file_import_charset"', $charset);
				$this->db->query($query);
				   
				if($format == 'csv')
				{
					$csv_columns = $_POST['csv_columns'];
					$csv_delimiter = $_POST['csv_delimiter'];
					$query = sql_placeholder('UPDATE settings SET value=? WHERE name="csv_import_columns"', $csv_columns);
					$this->db->query($query);
					$query = sql_placeholder('UPDATE settings SET value=? WHERE name="csv_import_delimiter"', $csv_delimiter);
					$this->db->query($query);
					$this->import_csv($fname, $csv_columns, $csv_delimiter);
				}

				$this->db->query('SET NAMES utf8');
				$query = 'UPDATE products SET order_num=product_id WHERE order_num=0';
				$this->db->query($query);
				$query = 'UPDATE products SET url=product_id WHERE url=""';
				$this->db->query($query);
				$query = 'UPDATE categories SET url=category_id WHERE url=""';
				$this->db->query($query);
				$query = 'UPDATE brands SET url=brand_id WHERE url=""';
				$this->db->query($query);
			}

			if($this->error_msg)
			{
				$this->smarty->assign('Error', $this->error_msg);
				$this->body = $this->smarty->fetch('import.tpl');
			}
			else
			{
				$this->smarty->assign('ProductsAdded', $this->products_added);
				$this->smarty->assign('ProductsUpdated', $this->products_updated);
				$this->smarty->assign('VariantsAdded', $this->variants_added);
				$this->smarty->assign('VariantsUpdated', $this->variants_updated);
				$this->body = $this->smarty->fetch('import_result.tpl');
			}

		}else
		{
			$this->smarty->assign('Lang', $this->lang);
			$this->smarty->assign('Error', $this->error_msg);
			$this->body = $this->smarty->fetch('import.tpl');
		}
	}
  
	//////////////////////
	//////////////////////  
	function process_category($name)
	{
		// Поле "категория" может состоять из нескольких имен, разделенных subcategory_delimiter-ом
		// Только неэкранированный subcategory_delimiter может разделять категории
		$delimeter = $this->subcategory_delimiter;
		$regex = "/\\DELIMETER((?:[^\\\\\DELIMETER]|\\\\.)*)/";
		$regex = str_replace('DELIMETER', $delimeter, $regex);
		$names = preg_split($regex, $name, 0, PREG_SPLIT_DELIM_CAPTURE);
		$result_category_id = null;   
		$current_parent = 0; 
		
		foreach($names as $name)
		{
			$name = trim(str_replace("\\$delimeter", $delimeter, $name));
			if(!empty($name))
			{
				$query = sql_placeholder("SELECT category_id FROM categories WHERE parent=? AND (name=? OR single_name=?) LIMIT 1", $current_parent, $name, $name);
				$this->db->query($query);
				$cat = $this->db->result();
						
				if(!empty($cat))
				{
					$result_category_id = $cat->category_id;
					$current_parent = $result_category_id;
				}
				else
				{
					$query = sql_placeholder("INSERT INTO categories(name, enabled, parent) VALUES(?, 0, ?)", $name, $current_parent);
					$this->db->query($query);
					$result_category_id = $this->db->insert_id();
					$current_parent = $result_category_id;		
				}
			}	
		}
		return $result_category_id;
	}

	//////////////////////
	//////////////////////  
	function process_brand($name)
	{
		$name = trim($name);
		if(!empty($name))
		{
			$query = sql_placeholder("SELECT * FROM brands WHERE name=? LIMIT 1", $name);
			$this->db->query($query);
			$exist_brand = $this->db->result();
			$brand_id = $exist_brand->brand_id;
			if(!empty($brand_id))
				return $brand_id;
          
			$query = sql_placeholder("INSERT INTO brands(name) VALUES(?)", $name);
			$this->db->query($query);
			$brand_id = $this->db->insert_id();
			$this->brands[$k]->brand_id = $brand_id;
			$this->brands[$k]->name = $name;
			return $brand_id;
		}
		return 0;
	}

	//////////////////////
	//////////////////////  
	function process_product($params)
	{
		if(isset($params['ctg'])) $category = trim($params['ctg']); else $category = '';
		if(isset($params['brnd'])) $brand = trim($params['brnd']); else $brand = '';
		if(isset($params['name'])) $model = trim($params['name']); else $model = '';
		if(isset($params['opt'])) $opt = trim($params['opt']); else $opt = '';
		if(isset($params['sku'])) $sku = trim($params['sku']); else $sku = '';
		if(isset($params['prc'])) $price = str_replace(',','.',$params['prc']); else $price = '';
		if(isset($params['qty'])) $quantity = intval($params['qty']); else $quantity = '';
		if(isset($params['ann'])) $description = trim($params['ann']); else $description = '';
		if(isset($params['dsc'])) $body = trim($params['dsc']); else $body = '';
		if(isset($params['url'])) $url = trim($params['url']); else $url = '';
		if(isset($params['mttl'])) $meta_title = trim($params['mttl']); else $meta_title = '';
		if(isset($params['mkwd'])) $meta_keywords = trim($params['mkwd']); else $meta_keywords = '';
		if(isset($params['mdsc'])) $meta_description = trim($params['mdsc']); else $meta_description = '';
		if(isset($params['enbld'])) $enabled = trim($params['enbld']); else $enabled = '';
		if(isset($params['hit'])) $hit = trim($params['hit']); else $hit = '';
		if(isset($params['simg'])) $small_image = trim($params['simg']); else $small_image = '';
		if(isset($params['limg'])) $large_image = trim($params['limg']); else $large_image = '';          
		if(isset($params['imgs'])) $images_string = trim($params['imgs']); else $images_string = '';   
		$images = split(',', $images_string);

		if((!empty($model) && !empty($category)) || !empty($sku))
		{ 
			$category_id = $this->process_category($category);
			$brand_id = $this->process_brand($brand);
			$found = null;
			if(!empty($sku))
			{
				$query = sql_placeholder("SELECT products_variants.variant_id,products_variants.product_id  FROM products_variants WHERE products_variants.sku=? LIMIT 1", $sku);
				$this->db->query($query);        
				$found = $this->db->result();
			}
			
			if(empty($found))
			{
				if(!empty($category_id))
					$q1 = sql_placeholder(" AND products.category_id=?", $category_id);
				if(!empty($opt))
					$q2 = sql_placeholder(" AND products_variants.name=?", $opt);
				if(!empty($brand_id))
					$q3 = sql_placeholder(" AND products.brand_id=?", $brand_id);
			
				$query = sql_placeholder("SELECT products.product_id as product_id FROM products LEFT JOIN products_variants ON products.product_id = products_variants.product_id $q2  WHERE products.model=? $q1  $q3 LIMIT 1", $model);
				$this->db->query($query);        
				$found = $this->db->result();
			}

			if(empty($found->product_id))
			{             
				$query = sql_placeholder('INSERT INTO products(url, category_id,  brand_id,  model, description,  body,  enabled,  hit,  meta_title,  meta_keywords,  meta_description, small_image, large_image, created, modified) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), now())',
																	$url, $category_id, $brand_id, $model, $description, $body, $enabled, $hit, $meta_title, $meta_keywords, $meta_description, $small_image, $large_image);
				if($this->db->query($query))
				{
					$product_id = $this->db->insert_id();
					$this->products_added++;
				}
			}
			else
			{
			   //update found product
			   $product_id = $found->product_id;
				
				$query_set = '';
				
				if(isset($params['ctg'])) $query_set .= 'category_id="'.mysql_real_escape_string($category_id).'", ';
				if(isset($params['brnd'])) $query_set .= 'brand_id="'.mysql_real_escape_string($brand_id).'", ';
				if(isset($params['name'])) $query_set .= 'model="'.mysql_real_escape_string($model).'", ';
				if(isset($params['ann'])) $query_set .= 'description="'.mysql_real_escape_string($description).'", ';
				if(isset($params['dsc'])) $query_set .= 'body="'.mysql_real_escape_string($body).'", ';
				if(isset($params['url'])) $query_set .= 'url="'.mysql_real_escape_string($url).'", ';
				if(isset($params['mttl'])) $query_set .= 'meta_title="'.mysql_real_escape_string($meta_title).'", ';
				if(isset($params['mkwd'])) $query_set .= 'meta_keywords="'.mysql_real_escape_string($meta_keywords).'", ';
				if(isset($params['mdsc'])) $query_set .= 'meta_description="'.mysql_real_escape_string($meta_description).'", ';
				if(isset($params['enbld'])) $query_set .= 'enabled="'.mysql_real_escape_string($enabled).'", ';
				if(isset($params['hit'])) $query_set .= 'hit="'.mysql_real_escape_string($hit).'", ';
				if(isset($params['simg'])) $query_set .= 'small_image="'.mysql_real_escape_string($small_image).'", ';
				if(isset($params['limg'])) $query_set .= 'large_image="'.mysql_real_escape_string($large_image).'", ';					
				
				$query = sql_placeholder("UPDATE products SET $query_set modified=now() WHERE product_id =?", $product_id);
				if($this->db->query($query))
					$this->products_updated++;
			   
			}
			
			if(empty($found->variant_id) && !empty($product_id))
			{
				$query = sql_placeholder('INSERT INTO products_variants(product_id, sku, name, price, stock) VALUES(?, ?, ?, ?, ?)',
																	$product_id, $sku, $opt, $price, $quantity);
				
				if($this->db->query($query))
				{
					$variant_id = $this->db->insert_id();
					$this->variants_added++;
				}
			}
			else
			{
				$variant_id = $found->variant_id;
				
				$query_set = '';
				
				if(isset($params['sku'])) $query_set[] = 'sku="'.mysql_real_escape_string($sku).'"';
				if(isset($params['opt'])) $query_set[] = 'name="'.mysql_real_escape_string($opt).'"';
				if(isset($params['prc'])) $query_set[] = 'price="'.mysql_real_escape_string($price).'"';
				if(isset($params['qty'])) $query_set[] = 'stock="'.mysql_real_escape_string($quantity).'"';
				
				if(!empty($query_set))
				{
					$query = sql_placeholder("UPDATE products_variants SET ". join(', ', $query_set) ." WHERE variant_id =?", $variant_id);
					
					if($this->db->query($query))
 						$this->variants_updated++;
 				}
	
			}      
			if(!empty($product_id))
			{
				if(!empty($images))
				{
					$i = 0;
					foreach($images as $image)
					{
						$image=trim($image);
						if(!empty($image))
						{
							$query = sql_placeholder('INSERT INTO products_fotos (product_id, foto_id, filename) VALUES(?, ?, ?)', $product_id, $i, trim($image));
							$this->db->query($query);
							$i++;
						}
					}
				}  
				return $product_id;         
			}
		}
		return false;
	}


	///////////////////////////////////////////
	///////////////////////////////////////////
	function import_csv($fname, $cols_order, $delimiter)
	{
		$handle = fopen($fname, "r");
		if(!$handle)
		{
			$this->error_msg = 'Не могу загрузить файл. Проверьте настройки сервера';
		}
		else
		{  
			// Максимальное время выполнения скрипта
			$max_time = @ini_get('max_execution_time');
			if(!$max_time)
				$max_time = 30;
			
			// Порядок колонок
			$temp = split(',', $cols_order);
			$i = 0;
			foreach($temp as $tmp)
			{
				$columns[trim($tmp)] = $i;
				$i++;
			}
			if(!((isset($columns['name']) && isset($columns['ctg'])) || isset($columns['sku'])))
			{
				$this->error_msg = 'Среди колонок должен присутствовать артикул или категория и название товара';
				return false;
			}
       
			$start_time = microtime(true);
			$time_elapsed = 0;
			$cols = true;
        
			# Идем по всем строкам
			while ($cols)
			{
				$cols = fgetcsv($handle, $this->csv_line_maxlength, $delimiter);

				foreach($columns as $name=>$index)
				{
					if(isset($cols[$index]))
						$values[$name] = $cols[$index];
					else
						$values[$name] = '';
				}

				$this->process_product($values);

 				$current_time = microtime(true);
				$time_elapsed = $current_time - $start_time;            
			}  
			fclose($handle);
		}
	}
}
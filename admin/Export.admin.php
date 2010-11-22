<?PHP

require_once('Widget.admin.php');
require_once('StorefrontGeneral.admin.php');
require_once('../placeholder.php');

############################################
# Class Setup displays news
############################################
class Export extends Widget
{
  var $subcategory_delimiter = '/';  
  function Export(&$parent)
  {
        parent::Widget($parent);
    $this->prepare();
  }

  function prepare()
  {
  
  }

  function fetch()
  {
    
    $this->title = 'Экспорт товаров';

        if(isset($_POST['format']) && !empty($_POST['format']))
        {
          if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
          {
            header('Location: http://'.$this->root_url.'/admin/');
            exit();
          }

      $format = $_POST['format'];
      
      // Кодировки
      $charset = $_POST['charset'];
     //  setlocale ( LC_ALL, 'ru_RU.'.$charset);
      
      $this->db->query('SET NAMES '.$charset);
      $query = sql_placeholder('UPDATE settings SET value=? WHERE name="file_export_charset"', $charset);
      $this->db->query($query);
      
      // 


    
      if($format == 'csv')
      {
        $csv_columns = $_POST['csv_columns'];
        $csv_delimiter = $_POST['csv_delimiter'];
        $query = sql_placeholder('UPDATE settings SET value=? WHERE name="csv_export_columns"', $csv_columns);
        $this->db->query($query);
        $query = sql_placeholder('UPDATE settings SET value=? WHERE name="csv_export_delimiter"', $csv_delimiter);
        $this->db->query($query);
        $this->export_csv($csv_columns, $csv_delimiter);
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

      if($this->error_msg)
      {
        $this->smarty->assign('Error', $this->error_msg);
            $this->body = $this->smarty->fetch('export.tpl');
      }

        }else
        {
            $this->smarty->assign('Lang', $this->lang);
        $this->smarty->assign('Error', $this->error_msg);
                $this->body = $this->smarty->fetch('export.tpl');
        }

  }
  

  ///////////////////////////////////////////
  ///////////////////////////////////////////
  function export_csv($cols_order, $delimiter)
  {
    $cassoc = array(
      'ctg'=>'category',
      'brnd'=>'brand',
      'name'=>'model',
      'sku'=>'sku',
      'prc'=>'price',
      'opt'=>'opt',
      'oprc'=>'old_price',
      'qty'=>'quantity',
      'ann'=>'description',
      'dsc'=>'body',
      'url'=>'url',
      'mttl'=>'meta_title',
      'mkwd'=>'meta_keywords',
      'mdsc'=>'meta_description',
      'enbld'=>'enabled',
      'hit'=>'hit',
      'simg'=>'small_image',
      'limg'=>'large_image'  
    );
   
  
      // Максимальное время выполнения скрипта
      $max_time = @ini_get('max_execution_time');
      if(!$max_time) $max_time = 30;
    
      // Порядок колонок
      $temp = split(',', $cols_order);
      $i = 0;
      foreach($temp as $tmp)
      {
        $columns[trim($tmp)] = $i;
        $i++;
      }
    
      $start_time = microtime(true);
      $time_elapsed = 0;
      
      # Выбераем товары
      $query = "SELECT SQL_CALC_FOUND_ROWS *,
                products.*, brands.name as brand,
                products_variants.sku as sku,
                products_variants.name as opt,
                products_variants.price as price,                
                products_variants.stock as quantity,                
                categories.category_id as category_id
                FROM categories, products LEFT JOIN brands ON products.brand_id = brands.brand_id
                LEFT JOIN products_fotos ON products.product_id = products_fotos.product_id
                LEFT JOIN products_variants ON products.product_id = products_variants.product_id
                WHERE 
                categories.category_id = products.category_id
                GROUP BY products_variants.variant_id
                ORDER BY categories.order_num, products.order_num, products_variants.position";

      $this->db->query($query);
      $products = $this->db->results();
      
      # Выбираем категории
      $cats = StorefrontGeneral::get_categories();
      $categories = StorefrontGeneral::categories_tree($cats);

      header("Content-Description: File Transfer");
      header("Content-Type: text/csv");
      header("Content-disposition: attachment; filename=simpla.csv");      
      # Идем по всем товарам
      $i = 0;
      while (isset($products[$i]) && $exec_time_ok = $time_elapsed < $max_time-1)
      { 
         $values = array();
         foreach($columns as $name=>$index)
         {
           if(!empty($cassoc[$name]))
           {
             $fieldname = $cassoc[$name];
             
             // Если нам нужна категория
             if($fieldname == 'category')
             {
                // выбираем категорию
                $category = StorefrontGeneral::category_by_id($categories, $products[$i]->category_id);
                $path_cats = null;
                $path_cats = array();
                // и по хлебным крошкам восстанавливаем путь к ней
                foreach($category->path as $c)
                {
                        $path_cats[] = str_replace('/','\/',$c->name);
                }
                                $values[] = join($this->subcategory_delimiter, $path_cats);
             }
             elseif(isset($products[$i]->$fieldname))
             {
                if(is_numeric($products[$i]->$fieldname))
                $values[] =  $products[$i]->$fieldname;
                                else                
                $values[] = str_replace('"', '""', $products[$i]->$fieldname);
             }
             else
               $values[] = '';
           }
         }
         $line = join($values, '"'.$delimiter.'"');
         print '"'.$line.'"'.PHP_EOL;

            
         $current_time = microtime(true);
         $time_elapsed = $current_time - $start_time;
         $i++;            
      }
      exit();
  
  }

}
<?php

function mysqlrestore($filename)
{
  $templine = '';
  $fp = fopen($filename, 'r');

  // Loop through each line
  if($fp)
  while(!feof($fp)) {
    $line = fgets($fp);
    // Only continue if it's not a comment
    if (substr($line, 0, 2) != '--' && $line != '') {
      // Add this line to the current segment
      $templine .= $line;
      // If it has a semicolon at the end, it's the end of the query
      if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
        mysql_query($templine) or print('Error performing query \'<b>' . $templine . '</b>\': ' . mysql_error() . '<br /><br />');
        // Reset temp variable to empty
        $templine = '';
      }
    }
  }

  fclose($fp);
}


function mysqldump($filename)
{
    $h = fopen($filename, 'w');
	$sql="show tables;";
	$result = mysql_query($sql);
	if( $result)
	{
		while( $row= mysql_fetch_row($result))
		{
			_mysqldump_table_structure($row[0], $h);
			_mysqldump_table_data($row[0], $h);
		}
	}
	else
	{
		echo "/* no tables in $mysql_database */\n";
	}
	mysql_free_result($result);
    fclose($h);
}

function _mysqldump_table_structure($table, $h)
{
	fwrite($h, "/* Table structure for table `$table` */\n");
    fwrite($h,  "DROP TABLE IF EXISTS `$table`;\n\n");
		$sql="show create table `$table`; ";
		$result=mysql_query($sql);
		if( $result)
		{
			if($row= mysql_fetch_assoc($result))
			{
				fwrite($h,  $row['Create Table'].";\n\n");
			}
		}
		mysql_free_result($result);

}

function _mysqldump_table_data($table, $h)
{

	$sql="select * from `$table`;";
	$result=mysql_query($sql);
	if( $result)
	{
		$num_rows= mysql_num_rows($result);
		$num_fields= mysql_num_fields($result);

		if( $num_rows > 0)
		{
			fwrite($h,  "/* dumping data for table `$table` */\n");

			$field_type=array();
			$i=0;
			while( $i < $num_fields)
			{
				$meta= mysql_fetch_field($result, $i);
				array_push($field_type, $meta->type);
				$i++;
			}

			//print_r( $field_type);
			fwrite($h,  "insert into `$table` values\n");
			$index=0;
			while( $row= mysql_fetch_row($result))
			{
				fwrite($h, "(");
				for( $i=0; $i < $num_fields; $i++)
				{
					if( is_null( $row[$i]))
						fwrite($h, "null");
					else
					{
						switch( $field_type[$i])
						{
							case 'int':
								fwrite($h,  $row[$i]);
								break;
							case 'string':
							case 'blob' :
							default:
								fwrite($h, "'".mysql_real_escape_string($row[$i])."'");

						}
					}
					if( $i < $num_fields-1)
						fwrite($h,  ",");
				}
				fwrite($h, ")");

				if( $index < $num_rows-1)
					fwrite($h,  ",");
				else
					fwrite($h, ";");
				fwrite($h, "\n");

				$index++;
			}
		}
	}
	mysql_free_result($result);
	fwrite($h, "\n");
}


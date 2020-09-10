<?php

//header('Content-type: text/html; charset=windows-1252');
// !! ODER !!
//header('Content-type: text/plain; charset=UFT-8');
// !! ODER !!
header('Content-type: text/plain; charset=windows-1252');

class MyClass
{

public static function convert_from_latin1_to_utf8_recursively($dat)
   {
      if (is_string($dat)) {
         return utf8_encode($dat);
      } elseif (is_array($dat)) {
         $ret = [];
         foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

         return $ret;
      } elseif (is_object($dat)) {
         foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

         return $dat;
      } else {
         return $dat;
      }
   }

}

$c = curl_init('https://seckendorffgym.de/vertretung/VertretungsPlanMo.htm');
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt(... other options you want...)

$html = curl_exec($c);

if (curl_error($c))
    die(curl_error($c));

// Get the status code
$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

curl_close($c);

/*$html = "dddasdfdddasdffff";
$needle = "tr";
$lastPos = 0;
$positions = array();

while (($lastPos = strpos($html, $needle, $lastPos))!== false) {
    $positions[] = $lastPos;
    $lastPos = $lastPos + strlen($needle);
}

// Displays 3 and 10
foreach ($positions as $value) {
    echo $value ."<br />";
}*/

//echo $html;

$save = false;

$wait = false;

$ubh1 = 0;
$ubh2 = 0;
$ubh3 = 0;
$ubh4 = 0;
$ubh5 = 0;
$ubh8 = 0;
$ubh9 = 0;
$ubh10 = 0;
$ubh11 = 0;

$bra = false;

$pub = 0;

$hinweise = array();

$first = false;

$jmphin = false;

$vertretung = array();

$eintrag = array();

$infos = array();

$rows = explode("<tr", $html);
$i = 0;

foreach ($rows as $row) {
	if($i != 0) {
		
		//$row = preg_replace( "/\r|\n|\r\n/", "", $row);
		$row = str_replace(array("\r", "\n"), "", $row);
		
		$columns = explode("<td", $row);
		$e = 0;
		
		/*echo "#############################";
		echo "-----------------------------";
		echo "#############################";*/
		
		foreach ($columns as $column) {
			
			//echo $column . "\n";
			
			if(strpos($column, "rowspan=") !== false) {
				//echo "JAAAAAAAAAAAAAAAA";
				$rs = explode("rowspan=", $column);
				$rs = $rs[1];
				//echo $rs;
				$pos = 0;
				while(substr($rs, $pos, 1) === "0" || substr($rs, $pos, 1) === "1" || substr($rs, $pos, 1) === "2" || substr($rs, $pos, 1) === "3" || substr($rs, $pos, 1) === "4" || substr($rs, $pos, 1) === "5" || substr($rs, $pos, 1) === "6" || substr($rs, $pos, 1) === "7" || substr($rs, $pos, 1) === "8" || substr($rs, $pos, 1) === "9") {
					$pos++;
				}
				$lenght = strlen($rs) - $pos;
				$rs = substr_replace($rs, "", $pos, $lenght);
				$rs = $rs - 1;
				
				$save = true;
				
				$wait = true;
				
				switch($e){
					case 1:
						$ubh1 = $rs;
						break;
					case 2:
						$ubh2 = $rs;
						break;
					case 3:
						$ubh3 = $rs;
						break;
					case 4:
						$ubh4 = $rs;
						break;
					case 5:
						$ubh5 = $rs;
						break;
					case 8:
						$ubh8 = $rs;
						break;
					case 9:
						$ubh9 = $rs;
						break;
					case 10:
						$ubh10 = $rs;
						break;
					case 11:
						$ubh11 = $rs;
						break;
					default:
						break;
				}
				
			}
			
			$column = str_replace("</td>", "", $column);
			
			$ex = explode(">", $column);
			
			$zw = explode("<span", $ex[1]);
			
			//echo "Es ist genau so lang: " .count($ex);
			
			if(count($ex)>2) {
				$en = explode("</span", $ex[2]);
				$str = $zw[0] . $en[0];
			} else {
				$str = $zw[0];
			}
			
			/*$str = "";
			$counter = 0;
			
			foreach($ex as $item) {
				if($counter != 0) {
					$str = $str . $item;
				}
				$counter++;
			}*/
			
			/*$n = 1;
			
			while (strpos($ex[$n], "<span") !== false || strpos($ex[$n], "</span") !== false) {
				$n++;
			}*/
			
			//$column = $ex[$n];
			
			$column = $str;
			
			$column = str_replace("</tr", "", $column);
			
			$column = str_replace("&nbsp;", " ", $column);
			
			/*$column = str_replace("<br />", "", $column);
			$column = str_replace("<br>", "", $column);
			
			$column = str_replace("&nl2bp;", "", $column);
			$column = str_replace("\n", "", $column);*/
			
			while (strpos($column, "  ") !== false) {
				$column = str_replace("  ", " ", $column);
			}
			
			while(substr($column, 0, 1) === " ") {
				$column = substr_replace($column, "", 0, 1);
			}
			
			while(substr($column, -1, 1) === " ") {
				$column = substr_replace($column, "", -1, 1);
			}
			
			
			if($e != 0 && $e <= 14) {
				//echo $e. ": " .$column. "\n";
				
				
				global $msg1;
				global $msg2;
				global $msg3;
				global $msg4;
				global $msg5;
				global $msg8;
				global $msg9;
				global $msg10;
				global $msg11;
				
				
				switch($e){
					case 1:
						$msg1 = $column;
						//echo "OBEN: " .$msg1;
						$title = "Klasse";
						
						/*$detect = "Veit-Ludwig-von-Seckendorff-Gymnasium";
						if(substr($column, 0, strlen($detect)) === $detect) {
							$extramsg = $column;
						}
						unset($detect);
						
						$detect = "Vertretungsplan";
						if(substr($column, 0, strlen($detect)) === $detect) {
							
							$date = explode("f", $column);
							$extramsg = "Vertretungsplan f" .$date[1];
							unset($date);
						}
						unset($detect);*/
						
						if($save){
							$subh1 = $column;
							$save = false;
						}
						
						
						break;
					case 2:
						$msg2 = $column;
						$title = "Stunde";
						if($save){
							$subh2 = $column;
							$save = false;
						}
						break;
					case 3:
						$msg3 = $column;
						$title = "Lehrer/in";
						if($save){
							$subh3 = $column;
							$save = false;
						}
						break;
					case 4:
						$msg4 = $column;
						$title = "Fach";
						if($save){
							$subh4 = $column;
							$save = false;
						}
						break;
					case 5:
						$msg5 = $column;
						$title = "Raum";
						if($save){
							$subh5 = $column;
							$save = false;
						}
						break;
					case 6:
						break;
					case 7:
						break;
					case 8:
						$msg8 = $column;
						$title = "Lehrer/in";
						if($save){
							$subh8 = $column;
							$save = false;
						}
						break;
					case 9:
						$msg9 = $column;
						$title = "Fach";
						if($save){
							$subh9 = $column;
							$save = false;
						}
						break;
					case 10:
						$msg10 = $column;
						$title = "Raum";
						if($save){
							$subh10 = $column;
							$save = false;
						}
						break;
					case 11:
						$msg11 = $column;
						$title = "Informationen / Bemerkungen";
						if($save){
							$subh11 = $column;
							$save = false;
						}
						break;
					case 12:
						if(strlen($column)>0){
							$msg11 = $msg11 . " " . $column;
						}
						break;
					case 13:
						if(strlen($column)>0){
							$msg11 = $msg11 . " " . $column;
						}
						break;
					case 14:
						if(strlen($column)>0){
							$msg11 = $msg11 . " " . $column;
						}
						break;
					default:
						break;
				}
				
				
				/*if($e == 14) {
					
				}*/
				
				
/*1: Klasse
  2: Std.
  3: Lehrer/in
  4: Fach
  5: Raum
  6: &nbsp;
  7: &nbsp;
  8: Lehrer/in<span
  style='mso-spacerun:yes'9: Fach
  10: Raum
  11: Informationen
  /Bemerku<span style='display:none'12: &nbsp;
  13: 
  14: 
*/


			}
			if(!$wait){
				if($ubh1 > 0 && $e == 1){
					$msg2 = $msg1;
					$e++;
					$msg1 = $subh1;
					$ubh1--;
					if($ubh1 == 0){
						unset($subh1);
					}
				}
				if($ubh2 > 0 && $e == 2){
					$msg3 = $msg2;
					$e++;
					$msg2 = $subh2;
					$ubh2--;
					if($ubh2 == 0){
						unset($subh2);
					}
				}
				if($ubh3 > 0 && $e == 3){
					$msg4 = $msg3;
					$e++;
					$msg3 = $subh3;
					$ubh3--;
					if($ubh3 == 0){
						unset($subh3);
					}
				}
				if($ubh4 > 0 && $e == 4){
					$msg5 = $msg4;
					$e++;
					$msg4 = $subh4;
					$ubh4--;
					if($ubh4 == 0){
						unset($subh4);
					}
				}
				if($ubh5 > 0 && $e == 5){
					//$msg6 = $msg5;
					$e++;
					$msg5 = $subh5;
					$ubh5--;
					if($ubh5 == 0){
						unset($subh5);
					}
				}
				if($ubh8 > 0 && $e == 8){
					$msg9 = $msg8;
					$e++;
					$msg8 = $subh8;
					$ubh8--;
					if($ubh8 == 0){
						unset($subh8);
					}
				}
				if($ubh9 > 0 && $e == 9){
					$msg10 = $msg9;
					$e++;
					$msg9 = $subh9;
					$ubh9--;
					if($ubh9 == 0){
						unset($subh9);
					}
				}
				if($ubh10 > 0 && $e == 10){
					$msg11 = $msg10;
					$e++;
					$msg10 = $subh10;
					$ubh10--;
					if($ubh10 == 0){
						unset($subh10);
					}
				}
				if($ubh11 > 0 && $e == 11){
					//$msg12 = $msg11;
					$e++;
					$msg11 = $subh11;
					$ubh11--;
					if($ubh11 == 0){
						unset($subh11);
					}
				}
			}
			
			$wait = false;
			
			$e++;
		}
		
		/*$detect = "Veit-Ludwig-von-Seckendorff-Gymnasium";
		//echo "UNTEN: " .$msg1;
		if(substr($msg1, 0, strlen($detect)) === $detect) {
			$extramsg = $msg1;
		}
		unset($detect);
		
		$detect = "Vertretungsplan";
		if(substr($msg1, 0, strlen($detect)) === $detect) {
			
			$date = explode("f", $msg1);
			$extramsg = "Vertretungsplan f" .$date[1];
			unset($date);
		}
		unset($detect);*/
		//echo $title. " (" .$e. "): " .$column;
		
		//echo "EMSG: " .$extramsg;
	
		
		$search  = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9","/");
		
		$msg1a = str_replace($search, "", $msg1);
		$msg2a = str_replace($search, "", $msg2);
		$msg3a = str_replace($search, "", $msg3);
		$msg4a = str_replace($search, "", $msg4);
		$msg5a = str_replace($search, "", $msg5);
		$msg8a = str_replace($search, "", $msg8);
		$msg9a = str_replace($search, "", $msg9);
		$msg10a = str_replace($search, "", $msg10);
		$msg11a = str_replace($search, "", $msg11);
		
		
		
		if($msg1 == $msg1a && $msg2 == $msg2a && $msg3 == $msg3a && $msg4 == $msg4a && $msg5 == $msg5a && $msg8 == $msg8a && $msg9 == $msg9a && $msg10 == $msg10a && $msg11 == $msg11a) {
			//echo "Zeile ".$i.": leer\n\n";
		} else {
			$dec = 0;
			if(strpos($msg1, "Klasse") !== false) {
				$dec++;
			}
			if(strpos($msg2, "Std.") !== false) {
				$dec++;
			}
			if(strpos($msg3, "Lehrer/in") !== false) {
				$dec++;
			}
			if(strpos($msg4, "Fach") !== false) {
				$dec++;
			}
			if(strpos($msg5, "Raum") !== false) {
				$dec++;
			}
			if(strpos($msg8, "Lehrer/in") !== false) {
				$dec++;
			}
			if(strpos($msg9, "Fach") !== false) {
				$dec++;
			}
			if(strpos($msg10, "Raum") !== false) {
				$dec++;
			}
			if(strpos($msg11, "Informationen /Bemerkungen") !== false) { 
				$dec++;
			}
			if($dec >5) {
				//echo "Zeile ".$i." ist der Tabellenkopf.\n";
				$bra = true;
				$pub = 1;
			}
			unset($dec);
			
			if(!$bra) {
				if(strpos($msg1, "Veit-Ludwig-von-Seckendorff-Gymnasium") !== false) {
					$infos["Schule"] = $msg1;
				}
				if(strpos($msg1, "Vertretungsplan f") !== false) {
					$infos["Datum"] = $msg1;
				}
				if(strpos($msg1, "Stand:") !== false) {
					if($msg3 != $msg3a){
						$dec = $msg2 . $msg3;
					} else {
						$dec = $msg2;
					}
					$infos["Stand"] = $dec;
					unset($dec);
				}
				if(strpos($msg1, "Hinweise:") !== false) {
					$dec = $msg2 . " " . $msg3 . " " . $msg4 . " " . $msg5 . " " . $msg8 . " " . $msg9 . " " . $msg10 . " " . $msg11;
					while (strpos($dec, "  ") !== false) {
						$dec = str_replace("  ", " ", $dec);
					}
					
					while(substr($dec, 0, 1) === " ") {
						$dec = substr_replace($dec, "", 0, 1);
					}
					
					while(substr($dec, -1, 1) === " ") {
						$dec = substr_replace($dec, "", -1, 1);
					}
					array_push($hinweise, $dec);
					$infos["Hinweise"] = $hinweise;
					unset($dec);
					$jmphin = true;
					$first = true;
				}
				if($jmphin) {
					
					if(strpos($msg1, "Betroffene Termine") !== false || strpos($msg4, "Vertretungen und") !== false) {
						$jmphin = false;
					} else {
						
						if($first) {
							$first = false;
						} else {
							$dec = $msg1 . " " . $msg2 . " " . $msg3 . " " . $msg4 . " " . $msg5 . " " . $msg8 . " " . $msg9 . " " . $msg10 . " " . $msg11;
							while (strpos($dec, "  ") !== false) {
								$dec = str_replace("  ", " ", $dec);
							}
							
							while(substr($dec, 0, 1) === " ") {
								$dec = substr_replace($dec, "", 0, 1);
							}
							
							while(substr($dec, -1, 1) === " ") {
								$dec = substr_replace($dec, "", -1, 1);
							}
							array_push($hinweise, $dec);
							$infos["Hinweise"] = $hinweise;
							unset($dec);
						}
					}
				}
			} else {
				if($pub > 2) {
					$dec = 0;
					if($msg1 != $msg1a){
						$eintrag["Klasse"] = $msg1;
						$dec++;
					}
					if($msg2 != $msg2a){
						$eintrag["Stunde"] = $msg2;
						$dec++;
					}
					if($msg3 != $msg3a){
						$eintrag["VorLehr"] = $msg3;
						$dec++;
					}
					if($msg4 != $msg4a){
						$eintrag["VorFach"] = $msg4;
						$dec++;
					}
					if($msg5 != $msg5a){
						$eintrag["VorRaum"] = $msg5;
						$dec++;
					}
					if($msg8 != $msg8a){
						$eintrag["NachLehr"] = $msg8;
						$dec++;
					}
					if($msg9 != $msg9a){
						$eintrag["NachFach"] = $msg9;
						$dec++;
					}
					if($msg10 != $msg10a){
						$eintrag["NachRaum"] = $msg10;
						$dec++;
					}
					if($msg11 != $msg11a){
						$eintrag["Infos"] = $msg11;
						$dec++;
					}
					if($dec > 0) {
						$eintrag["ID"] = $i;
						array_push($vertretung, $eintrag);
						$infos["Vertretung"] = $vertretung;
					}
				}
				unset($dec);
				unset($eintrag);
			}
			
			
			/*echo "Zeile ".$i.":\n";
			echo "MSG1: ".$msg1."\n";
			echo "MSG2: ".$msg2."\n";
			echo "MSG3: ".$msg3."\n";
			echo "MSG4: ".$msg4."\n";
			echo "MSG5: ".$msg5."\n";
			echo "MSG8: ".$msg8."\n";
			echo "MSG9: ".$msg9."\n";
			echo "MSG10: ".$msg10."\n";
			echo "MSG11: ".$msg11."\n\n";*/
		}
		
		//echo "Klasse ".$msg1." hat in der ".$msg2." Stunde statt ".$msg4." bei ".$msg3." im Raum ".$msg5." bei ".$msg8." ".$msg9." im Raum ".$msg10." (".$msg11.")";
		
		//echo "[!]";
		
		
		
		//echo $column;
		unset($column);
		
	}
	$i++;
	if($pub > 0){
		$pub++;
	}
}
unset($row);
//print_r($infos);

$y = new MyClass();

$infos = $y->convert_from_latin1_to_utf8_recursively($infos);

//$infos = convert_from_latin1_to_utf8_recursively($infos);

echo json_encode($infos);

//echo $rows[0]; // Teil1



/*$rows = array();
$obj = (object) $html;
$rows = $obj->find('tr'); // Find all rows in the table
//Loop through each row
foreach ($rows as $row) {
    //Loop through each child (cell) of the row
    foreach ($row->children() as $cell) {
        echo $cell->plaintext; // Display the contents of each cell - this is the value you want to extract
    }
}*/

?>
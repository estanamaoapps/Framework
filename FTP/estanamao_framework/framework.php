<?php
	
	ini_set('display_errors', 1);		
	
	$loaded_urls = array();
	$head = "";
	$body = "";
	
	function GetRequire($_content, $_once = true){
		global $loaded_urls;
		
		$data = "";
		
		if($_once == false)
			$array = explode("<!-- require ", $_content);
		else
			$array = explode("<!-- require_once ", $_content);
		if(count($array)  >= 2){
			$array2 = explode(" -->", $array[1]);
			if(count($array2)  >= 2){				
				$require_code = file_get_contents($array2[0]);						
				if(empty($require_code) == false){
					$res = GetRequire($require_code, true);
					if(empty($res) == false)	$data .= $res;
					
					$res = GetRequire($require_code, false);
					if(empty($res) == false)	$data .= $res;
					
					$data .= $require_code;
				}
				$loaded_urls[] = $array2[0];
			}
		}
		
		return $data;
	}
	
	// HEAD
	if(empty($_GET["head"]) == false){
		$array = json_decode($_GET["head"], true);
		foreach($array as $i=>$v){
			$loaded_urls[] = $v;
			$code = file_get_contents($v);
			
			$head .= GetRequire($code, true);
			$head .= GetRequire($code, false);			
			$head .= $code;			
		}
	}
	
	// BODY
	if(empty($_GET["body"]) == false){
		$array = json_decode($_GET["body"], true);
		foreach($array as $i=>$v){
			$loaded_urls[] = $v;
			$code = file_get_contents($v);
		
			$body .= GetRequire($code, true);
			$body .= GetRequire($code, false);			
			$body .= $code;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>		
		<? echo $head; ?>
	</head>
	<body>
		<script type="text/javascript">
			var GET = <? echo json_encode($_GET); ?>;
			var POST = <? echo json_encode($_POST); ?>;
		</script>
	
		<? echo $body; ?>
	</body>
</html>


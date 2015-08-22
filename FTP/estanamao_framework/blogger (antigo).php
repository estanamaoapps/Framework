<?php
	/***********************************************************************************************
	
	 Faz alterações no XML do feed para retornar uma 
	 visualização melhor para o aplicativo.
	 
	 ***********************************************************************************************/
	
	
	
	/*$feed = file_get_contents($_GET["url"]);
	
	// remove a tag "author"
	$feed = str_replace('<author', '<hidedata', $feed);
	$feed = str_replace('author>', 'hidedata>', $feed);	

	// remove a thumbnail
	if($_GET["thumb"] == "no")
		$feed = str_replace('<description>', '<description>&lt;br /&gt;&lt;br /&gt;&lt;img border="0" src="" style="display:none;" /&gt;', $feed);		
	
	echo $feed;*/
	
	ini_set('display_errors',1);
	date_default_timezone_set ( "America/Sao_Paulo" );
	
	$mode = "0";
	if(empty($_GET["m"]) == false)$mode = $_GET["m"];
	// 1 - Data no lugar do autor.
	// 2 - Data no lugar do autor e sem thumbnail
	
	
	$feed = file_get_contents($_GET["url"]);
	$xml = simplexml_load_string($feed);
	
	foreach($xml->channel->children() as $child){
		if($child->getName() == "item"){
			
			// THUMB
			if($mode == "2"){	
				$c = $child->children("media", true);
				for($i=0; $i<count($c); $i++){							
					if($mode == "2") unset($c[$i]); // remove a thumb
				}
			}
			
			// DESCRIPTION
			// insere uma imagem falsa e escondida, para o feed não pegar outras imagem do texto como thumbnail
			if($mode == "2"){				
				$child->description[0] = '<img border="0" src="" style="display:none;" />' . $child->description[0];
			}
			
			// AUTHOR
			if($mode == "1" || $mode == "2"){
				for($i=0; $i<count($child->author); $i++){
					if($mode == "1" || $mode == "2")
						$child->author[$i] = "noreply@blogger.com (" . date("d/m/Y", strtotime($child->pubDate[0])) . ")";
				}
			}
		}
	}
	
	echo $xml->asXML();
?>
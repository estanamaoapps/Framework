<?php
	/*******************************************************************
	
		Faz apenas o carregamento do feed, pois a plataforma,
		por motivos de segurança, não carrega diretamente via
		ajax.
	
	*******************************************************************/
	
	ini_set('display_errors', 1);	
	//$_GET["url"] = "http://www.estanamaoappsfernandocury.blogspot.com/feeds/posts/default/-/A%20Semana%20do%20Deputado/?alt=json";	
	$feed = file_get_contents($_GET["url"]);
	
	echo $feed;

?>
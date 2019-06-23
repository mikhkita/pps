<?php

$xml = simplexml_load_file("test.xml");



foreach( $xml->Документ as $item ){
	echo "<pre>";
	
	foreach ($item->Пассажиры->Пассажир as $key => $pass) {
		var_dump($pass);
	}
	echo "</pre>";	
}
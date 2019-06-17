<?php
$url = "https://api.getresponse.com/v3/contacts";
if(isset($_POST["email"]))
{

	$ch = curl_init($url . "?query[campaignId]=n5kac&query[email]=" . $_POST["email"]);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Token: api-key 314a1e7a6132bd8f35779282ad3e7b25'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_GET, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$result = curl_exec($ch);
	curl_close($ch);
	
	if($result === "[]")
	{

		$json = json_encode([
			'email' => $_POST["email"],
			'campaign' => [
				'campaignId' => "n5kac"
			]
		]);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Token: api-key 314a1e7a6132bd8f35779282ad3e7b25'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		$result = curl_exec($ch);
		//echo $result;
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch); 
		if($result === "[]")
		{
			echo 0;
		}
		else
		{
			echo $result;
		}
	}
	else
	{
		echo 1;
	}
}
?>
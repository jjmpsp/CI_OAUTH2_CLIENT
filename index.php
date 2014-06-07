<?php
	$CLIENT_ID 		= "test";
	$CLIENT_SECRET 	= "test";
	$REDIRECT_URL	= "http://localhost2/oauth2_myapptest";
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>MyAppTest</title>
</head>
<body>
	<h1>Welcome to MyAppTest</h1>
	<p>This is an amazing website where you can do some cool things...</p>
	<h2>Connect with [NameOfService]</h2>
	<a href="http://localhost2/oauth2/oauth2/?client_id=<?php echo $CLIENT_ID; ?>&scope=profile_information,profile_information2&redirect_uri=<?php echo $REDIRECT_URL; ?>&response_type=code">Add a [NameOfService] account</a>
	<hr>
</body>
</html>

<?php
	if( empty( $_GET['error'] ) ){
		if( !empty( $_GET['code'] ) ){
			echo "We got a request token: ". $_GET['code'];
			echo "<br>";
			echo "Now time to use the request token and send a post request for an access token.";
			echo "<br><br>";

			$url = 'http://localhost2/oauth2/oauth2/access_token';
			$body = 'grant_type=authorization_code&client_id='.$CLIENT_ID.'&client_secret='.$CLIENT_SECRET.'&redirect_uri='.$REDIRECT_URL.'&code='.$_GET['code'];
			
			// cURL resource
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($response, true);

			if(!empty($json['error'])){
				echo $json['error_description'];
			}else{
				echo 'Success! You can now make requests with the access_token: '.$json['access_token'];
				echo "<br>";
				echo 'Developers can store the token for a particular user id or session for: '.($json['expires_in']/60)." minutes";
			}
		}
	}else{
		echo $_GET['error_message'];
	}
?>
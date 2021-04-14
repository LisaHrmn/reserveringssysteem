<?php
require_once "session.php";

$dogApiUrl = "https://dog.ceo/api/breeds/image/random";
$curl = curl_init();

curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $dogApiUrl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_VERBOSE, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$curlResult = curl_exec($curl);

curl_close($curl);
$data = json_decode($curlResult);

if ( isset( $_POST['go_back'] ) ) {
	header( 'Location: reservation.php' );
}

if ( isset( $_POST['but_logout'] ) ) {
	session_destroy();
	header( 'Location: login.php' );
}
?>

<!<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Succes</title>
		<link rel="stylesheet" href="css/style.css">
	</head>

	<body>
        <header id="header">
            <p>Adinda Bijles</p>
        </header>
        <div class="container">
            <div class="message">
                <p>De afspraak staat ingepland!</p>
                <p>Geniet van deze hondenfoto als bedankje</p>
                <div class="api">
                    <img src="<?php echo $data->message?>"/>
                </div>
            </div>
		    <div class="back2">
			    <form method='post' action="">
				    <input type="submit" value="Terug Naar Reserveringsformulier" name="go_back" id="form_back">
			    </form>
		    </div>

            <div class="back">
                <form method='post' action="">
                    <input type="submit" value="uitloggen" name="but_logout">
                </form>
            </div>
        </div>
	</body>
</html>

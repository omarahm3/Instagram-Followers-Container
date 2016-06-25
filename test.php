<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Instagram follower Getter</title>
  <meta name="author" content="Christian Metz">
  <style>
    * {
      margin: 0;
      padding: 0;
    }
    article, aside, figure, footer, header, hgroup, 
    menu, nav, section { display: block; }
    ul > li {
      list-style: none;
      padding: 4px;
    }
    .avatar {
      background-size: 40px auto;
      border-radius: 50%;
      display: inline-block;
      height: 40px;
      margin-right: 8px;
      vertical-align: middle;
      width: 40px;
    }
  </style>
</head>
<body>

<?php
	/**
	 * Instagram PHP API
	 * show list of users a user is followed by
	 * 
	 * @link https://github.com/cosenary/Instagram-PHP-API
	 * @author Christian Metz
	 * @since 01.10.2013
	 */
	require 'Instagram.php';
	use MetzWeb\Instagram\Instagram;
	// Initialize class
	$instagram = new Instagram(array(
	  'apiKey'      => '06bb95f2afd343eba16787f55a84d353',
	  'apiSecret'   => 'c819c6c905f140a294f537efb50196bf',
	  'apiCallback' => 'http://localhost/insta/test.php'
	));

	echo "<a href='{$instagram->getLoginUrl()}'>Login With Instagram</a>";

	$code = $_GET['code'];
	if (true === isset($code)) {
		$data = $instagram->getOAuthToken($code);
		echo 'Your username is: ' . $data->user->username;

		$instagram->setAccessToken($data);
		$follower = $instagram->getUserFollower();

		$str = "";

		echo "<ul>";
		do {
			// loop through all entries of a response
			foreach ($follower->data as $data) {
			  	echo "<li><div class=\"avatar\" style=\"background-image: url({$data->profile_picture})\"></div> $data->username ($data->full_name)</li>";
			  	$str = $str . ", " . $data->username . "(" . $data->full_name . ")";
			}
		// continue with the next result
		} while ($follower = $instagram->pagination($follower));
		echo "</ul>";

		function maybeEncodeCSVField($string) {
		    if(strpos($string, ',') !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
		        $string = '"' . str_replace('"', '""', $string) . '"';
		    }
		    return $string;
		}

		echo "<br> STRING:" . maybeEncodeCSVField($str);

		echo $str;

		

		function download_csv_results($results, $name = NULL)
		{
		    if( ! $name)
		    {
		        $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
		    }

		    header('Content-Type: text/csv');
		    header('Content-Disposition: attachment; filename='. $name);
		    header('Pragma: no-cache');
		    header("Expires: 0");

		    $outstream = fopen("php://output", "w");

		    foreach($results as $result)
		    {
		        fputcsv($outstream, $result);
		    }

		    fclose($outstream);
		}


		//download_csv_results($str, "Followers.csv");

		function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
		    header('Content-Type: application/csv');
		    header('Content-Disposition: attachment; filename="'.$filename.'";');

		    // open the "output" stream
		    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
		    $f = fopen('php://output', 'w');

		    foreach ($array as $line) {
		        fputcsv($f, $line, $delimiter);
		    }
		} 



	}
?>

</body>
</html>
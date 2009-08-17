<?php

require_once 'lib/2leggedOAuthRequestGenerator.php';

$api_url = 'http://r-weblife.sakura.ne.jp/SignedRequest/example.php';
$opensocial_appurl = 'http://r-weblife.sakura.ne.jp/SignedRequest/SignedRequest.xml';

$consumerkey = 'r-weblife.sakura.ne.jp';
$consumersecret = 'r-weblife.sakura.ne.jp.secret';

$params = array(
    "opensocial_app_url" => $opensocial_appurl,
    "opensocial_param_a" => "testValueA",
    "opensocial_param_b" => "testValueB"
	);

$ur = "";
$response = "";
$generator = new twoLeggedOAuthRequestGenerator( $api_url, $consumerkey, $consumersecret, 'HMAC-SHA1', 'GET', $params );

if( $generator ){
	$url = $generator->getUrl();
	$response = getResponse( $url );
}else{
	print "sorry...";
	exit;
}

function getResponse($url, $post_data = null) {
  $ch = curl_init();
  if (defined("CURL_CA_BUNDLE_PATH")) curl_setopt($ch, CURLOPT_CAINFO, CURL_CA_BUNDLE_PATH);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  if (isset($post_data)) {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  }
  $response = curl_exec($ch);
  curl_close ($ch);
  return $response;
}

?>
<html>
<body>
<h2>2legged OAuth Test (HMAC-SHA1 Signature) </h2>
<p>request :</p>
<?php print nl2br( str_replace( "&", "\n&", $url ) ); ?>
<br />
<p>response : </p>
<pre>
<?php print $response; ?>
</pre>
<p><a href="./2leggedOAuthRequestGeneratorTest_RSA.php">RSA-SHA1</a></p>
<p><a href="./2leggedOAuthRequestGeneratorTest_HMAC.php">HMAC-SHA1</a></p>
</body>
</html>

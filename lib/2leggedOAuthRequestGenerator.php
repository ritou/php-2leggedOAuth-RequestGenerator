<?php

require_once 'OAuth.php';

class twoLeggedOAuthSignatureMethod_RSA_SHA1 extends OAuthSignatureMethod_RSA_SHA1 {
  public $cert;

  function __construct( $consumerKey ) {
    $this->set_private_cert( $consumerKey );
  }  
  
  public function fetch_private_cert( &$request ) {
    return $this->cert;
  }
  
  public function set_private_cert( $consumerKey ) {
    global $privatekeys;
    if ($privatekeys[$consumerKey]) {
      $this->cert = $privatekeys[$consumerKey]['privatekey'];
      return true;
    } else {
      return false;
    }
  }
}

class twoLeggedOAuthRequestGenerator{

  private $request;
  private $url;

  function __construct( $access_url, $consumer_key, $consumer_secret = NULL, $signature_method = 'RSA-SHA1', $method = 'GET', $params = NULL ){
    if( !$access_url || !$consumer_key ){
		return false;
	}
	
    $consumer = new OAuthConsumer( $consumer_key, $consumer_secret );
    $this->request = OAuthRequest::from_consumer_and_token($consumer, NULL, $method, $access_url, $params);
	
	if( $signature_method == 'RSA-SHA1' ){
    	$signature_method = new twoLeggedOAuthSignatureMethod_RSA_SHA1( $consumer_key );
		$this->request->sign_request($signature_method, $consumer, NULL);
	}elseif( $signature_method == 'HMAC-SHA1' ){
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->request->sign_request($signature_method, $consumer, NULL);
	}else{
		return false;
	}
  }
  
  function getUrl(){
  	return $this->request->to_url();
  }
}

?>

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

  function __construct( $access_url, $consumer_key, $consumer_secret = NULL, $method = "GET", $params = NULL ){
    if( !$access_url || !$consumer_key ){
		return false;
	}
	
    $consumer = new OAuthConsumer( $consumer_key, $consumer_secret );
    $signature_method = new twoLeggedOAuthSignatureMethod_RSA_SHA1( $consumer_key );

    $this->request = OAuthRequest::from_consumer_and_token($consumer, $consumer_secret, $method, $access_url, $params);
    $this->request->sign_request($signature_method, $consumer, '');
  }
  
  function getUrl(){
  	return $this->request->to_url();
  }
}

?>
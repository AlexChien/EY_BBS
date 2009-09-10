<?php
/*
* Created on 23-jun-2007. Based upon class.openid.php
* Changed classname from SimpleOpenID to OpenIDService

FREE TO USE
Simple OpenID PHP Class
Contributed by http://www.fivestores.com/
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
This Class was written to make easy for you to integrate OpenID on your website.
This is just a client, which checks for user's identity. This Class Requires CURL Module.
It should be easy to use some other HTTP Request Method, but remember, often OpenID servers
are using SSL.
We need to be able to perform SSL Verification on the background to check for valid signature.
HOW TO USE THIS CLASS:
STEP 1)
$openid = new SimpleOpenID;
:: SET IDENTITY ::
$openid->SetIdentity($_POST['openid_url']);
:: SET RETURN URL ::
$openid->SetApprovedURL('http://www.yoursite.com/return.php'); // Script which handles a response from OpenID Server
:: SET TRUST ROOT ::
$openid->SetTrustRoot('http://www.yoursite.com/');
:: FETCH SERVER URL FROM IDENTITY PAGE :: [Note: It is recomended to cache this (Session, Cookie, Database)]
$openid->GetOpenIDServer(); // Returns false if server is not found
:: REDIRECT USER TO OPEN ID SERVER FOR APPROVAL ::

:: (OPTIONAL) SET OPENID SERVER ::
$openid->SetOpenIDServer($server_url); // If you have cached previously this, you don't have to call GetOpenIDServer and set value this directly

STEP 2)
Once user gets returned we must validate signature
:: VALIDATE REQUEST ::
true|false = $openid->ValidateWithServer();

ERRORS:
array = $openid->GetError(); // Get latest Error code

FIELDS:
OpenID allowes you to retreive a profile. To set what fields you'd like to get use (accepts either string or array):
$openid->SetRequiredFields(array('email','fullname','dob','gender','postcode','country','language','timezone'));
or
$openid->SetOptionalFields('postcode');

IMPORTANT TIPS:
OPENID as is now, is not trust system. It is a great single-sign on method. If you want to
store information about OpenID in your database for later use, make sure you handle url identities
properly.
For example:
https://steve.myopenid.com/
https://steve.myopenid.com
http://steve.myopenid.com/
http://steve.myopenid.com
... are representing one single user. Some OpenIDs can be in format openidserver.com/users/user/ - keep this in mind when storing identities
To help you store an OpenID in your DB, you can use function:
$openid_db_safe = $openid->OpenID_Standarize($upenid);
This may not be comatible with current specs, but it works in current enviroment. Use this function to get openid
in one format like steve.myopenid.com (without trailing slashes and http/https).
Use output to insert Identity to database. Don't use this for validation - it may fail.
*/
class OpenIDService {
var $openid_url_identity;
var $URLs = array();
var $error = array();
var $fields = array();

function & getInstance(& $db) {
static $instance;
if (!isset ($instance))
$instance = & new OpenIDService($db);
return $instance;
}

function OpenIDService(){
if (!function_exists('curl_exec')) {
die('Error: Class OpenIDService requires curl extension to work');
}

}
function SetOpenIDServer($a){
$this->URLs['openid_server'] = $a;
}
function SetTrustRoot($a){
$this->URLs['trust_root'] = $a;
}
function SetCancelURL($a){
$this->URLs['cancel'] = $a;
}
function SetApprovedURL($a){
$this->URLs['approved'] = $a;
}
function SetRequiredFields($a){
if (is_array($a)){
$this->fields['required'] = $a;
}else{
$this->fields['required'][] = $a;
}
}
function SetOptionalFields($a){
if (is_array($a)){
$this->fields['optional'] = $a;
}else{
$this->fields['optional'][] = $a;
}
}
function SetIdentity($a){ // Set Identity URL
// Prepend http:// if not present and append / if (probably) just a domain name, e.g: mytest.myopenid.com
if ((strpos($a, 'http://') === false) && (strpos($a, 'https://') === false)) {

// If no / in the url, then it should be a domain (right?) so append a /
if (strpos($a, '/') === false) {
$a = $a . '/';
} else {
}
$a = 'http://'.$a;

} else {
// Starts already with http or https. Now if there's no / after the http(s):// then append one, because then it's probably a domain (right?)
if ((strpos($a, '/', strlen('http://')) === false) || (strpos($a, '/', strlen('https://')) === false)) {
$a = $a . '/';
} else {
// Already has a slash, dont append anything
}
}

/*
$u = parse_url(trim($a));
if (!isset($u['path'])){
$u['path'] = '/';
}else if(substr($u['path'],-1,1) == '/'){
$u['path'] = substr($u['path'], 0, strlen($u['path'])-1);
}
if (isset($u['query'])){ // If there is a query string, then use identity as is
$identity = $a;
}else{
$identity = $u['scheme'] . '://' . $u['host'] . $u['path'];
}*/

$this->openid_url_identity = $this->GlueURL(parse_url($a)); // Should do this because part after the domain shouldnt be lowercase! strtolower($a);
// So we do a more sophistcated one. Note that it is already a full URL here, so that's why we can use parse_url() here.
// Almmost HAVE to do the strtolower() otherwise on the server Bobby.myopenid.com
// and Mytest.myopenid.com just return an empty response in GetOpenIdServer()!!!
// It works fine locally. It also works fine for the openid-example.php if you
// use uppercase. Really dont know.....

}
function GetIdentity(){ // Get Identity
return $this->openid_url_identity;
}
function GetError(){
$e = $this->error;
return array('code'=>$e[0],'description'=>$e[1]);
}
function ErrorStore($code, $desc = null){
$errs['OPENID_NOSERVERSFOUND'] = 'Cannot find OpenID Server TAG on Identity page.';
if ($desc == null){
$desc = $errs[$code];
}
$this->error = array($code,$desc);
}
function IsError(){
if (count($this->error) > 0){
return true;
}else{
return false;
}
}

function splitResponse($response) {
$r = array();
$response = explode("\n", $response);
foreach($response as $line) {
$line = trim($line);
if ($line != "") {
list($key, $value) = explode(":", $line, 2);
$r[trim($key)] = trim($value);
}
}
return $r;
}

function OpenID_Standarize($openid_identity){
$u = parse_url(strtolower(trim($openid_identity)));
if ($u['path'] == '/'){
$u['path'] = '';
}
if(substr($u['path'],-1,1) == '/'){
$u['path'] = substr($u['path'], 0, strlen($u['path'])-1);
}
if (isset($u['query'])){ // If there is a query string, then use identity as is
return $u['host'] . $u['path'] . '?' . $u['query'];
}else{
return $u['host'] . $u['path'];
}
}

function array2url($arr){ // converts associated array to URL Query String
if (!is_array($arr)){
return false;
}
foreach($arr as $key => $value){
$query .= $key . "=" . $value . "&";
}
return $query;
}

// Not used, probably for testing purposes.
// function FSOCK_Request($url, $method="GET", $params = ""){
// $fp = fsockopen("ssl://www.myopenid.com", 443, $errno, $errstr, 3); // Connection timeout is 3 seconds
// if (!$fp) {
// $this->ErrorStore('OPENID_SOCKETERROR', $errstr);
// return false;
// } else {
// $request = $method . " /server HTTP/1.0\r\n";
// $request .= "User-Agent: Simple OpenID PHP Class (http://www.phpclasses.org/simple_openid)\r\n";
// $request .= "Connection: close\r\n\r\n";
// fwrite($fp, $request);
// stream_set_timeout($fp, 4); // Connection response timeout is 4 seconds
// $res = fread($fp, 2000);
// $info = stream_get_meta_data($fp);
// fclose($fp);
//
// if ($info['timed_out']) {
// $this->ErrorStore('OPENID_SOCKETTIMEOUT');
// } else {
// return $res;
// }
// }
// }
function CURL_Request($url, $method="GET", $params = "") { // Remember, SSL MUST BE SUPPORTED
if (is_array($params)) $params = $this->array2url($params);
// Append params if GET, but dont forget, there might already be a ? in the server url!
// In that case, append a &, not a ?
if (strpos($url, "?") === FALSE) {
$theURL = $url . ($method == "GET" && $params != "" ? "?" . $params : "");
$curl = curl_init($theURL);
} else {
$theURL = $url . ($method == "GET" && $params != "" ? "&" . $params : "");
$curl = curl_init($theURL);
}

curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HTTPGET, ($method == "GET"));
curl_setopt($curl, CURLOPT_POST, ($method == "POST"));
if ($method == "POST") curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Was: $response = curl_exec($curl); Works.

// This one also handles redirects when disabled on server
$response = $this->curl_redir_exec($curl);

if (curl_errno($curl) == 0){
$response;
}else{
$this->ErrorStore('OPENID_CURL', curl_error($curl));
}

$header = curl_getinfo( $curl ); // Want so see if anything else maybe went wrong.

curl_close($curl); // Free resources
return $response;
}

function HTML2OpenIDServer($content) {
$get = array();
// Get details of their OpenID server and (optional) delegate
preg_match_all('/<link[^>]*rel=["|\']openid.server["|\'][^>]*href="([^"]+)"[^>]*\/?>/i', $content, $matches1);
preg_match_all('/<link[^>]*href="([^"]+)"[^>]*rel=["|\']openid.server["|\'][^>]*\/?>/i', $content, $matches2);
$servers = array_merge($matches1[1], $matches2[1]);

preg_match_all('/<link[^>]*rel=["|\']openid.delegate["|\'][^>]*href="([^"]+)"[^>]*\/?>/i', $content, $matches1);

preg_match_all('/<link[^>]*href="([^"]+)"[^>]*rel=["|\']openid.delegate["|\'][^>]*\/?>/i', $content, $matches2);

$delegates = array_merge($matches1[1], $matches2[1]);

$ret = array($servers, $delegates);
return $ret;
}

function GetOpenIDServer(){

$response = $this->CURL_Request($this->openid_url_identity);

list($servers, $delegates) = $this->HTML2OpenIDServer($response);
if (count($servers) == 0){
$this->ErrorStore('OPENID_NOSERVERSFOUND');
return false;
} else {
// Found at least 1 server
}
if ($delegates[0] != ""){
$this->openid_url_identity = $delegates[0];
}
$this->SetOpenIDServer($servers[0]);
return $servers[0];
}

function GetRedirectURL(){
$params = array();
$params['openid.return_to'] = urlencode($this->URLs['approved']);
$params['openid.mode'] = 'checkid_setup';
$params['openid.identity'] = urlencode($this->openid_url_identity);
$params['openid.trust_root'] = urlencode($this->URLs['trust_root']);

if (count($this->fields['required']) > 0){
$params['openid.sreg.required'] = urlencode(implode(',',$this->fields['required']));
}
if (count($this->fields['optional']) > 0){
$params['openid.sreg.optional'] = urlencode(implode(',',$this->fields['optional']));
}
// Append params if GET, but dont forget, there might already be a ? in the server url!
// In that case, append a &, not a ?
if (strpos($this->URLs['openid_server'], "?") === FALSE) {
return $this->URLs['openid_server'] . "?". $this->array2url($params);
} else {
return $this->URLs['openid_server'] . "&". $this->array2url($params);
}
}

function Redirect(){
$redirect_to = $this->GetRedirectURL();

if (headers_sent()){ // Use JavaScript to redirect if content has been previously sent (not recommended, but safe)
echo '<script language="JavaScript" type="text/javascript">window.location=\'';
echo $redirect_to;
echo '\';</script>';
}else{ // Default Header Redirect
header('Location: ' . $redirect_to);
}
}

function ValidateWithServer(){
$params = array(
'openid.assoc_handle' => urlencode($_GET['openid_assoc_handle']),
'openid.signed' => urlencode($_GET['openid_signed']),
'openid.sig' => urlencode($_GET['openid_sig'])
);
// Send only required parameters (the ones from the signed list, that is: openid_signed) to confirm validity
// Why o why are they with an '_' in them???
// Because PHP engine converts '.' in request params to '_'!!
$arr_signed = explode(",",str_replace('sreg.','sreg_',$_GET['openid_signed']));
for ($i=0; $i<count($arr_signed); $i++){
$s = str_replace('sreg_','sreg.', $arr_signed[$i]);
$c = $_GET['openid_' . $arr_signed[$i]];
// if ($c != ""){
$params['openid.' . $s] = urlencode($c);
// }
}
$params['openid.mode'] = "check_authentication";
// DEBUG: print "<pre>";
// DEBUG: echo "<br/>Dumping GET:<br/>"; print_r($_GET);
// DEBUG: echo "<br/>Dumping params:<br/>"; print_r($params);
// DEBUG: print "</pre>";
$openid_server = $this->GetOpenIDServer();
if ($openid_server == false){
return false;
}
$response = $this->CURL_Request($openid_server,'POST',$params);
$data = $this->splitResponse($response);

// DEBUG: echo "<br/>Dumping response:<br/>";
// DEBUG: print($response);
// DEBUG: echo "<br/>Dumping data:<br/>"; print_r($data);
// DEBUG: echo "<br/>Dumping GET:<br/>"; print_r($_GET);
// DEBUG: echo "<br/>Dumping POST:<br/>"; print_r($_POST);

if ($data['is_valid'] == "true") {
return true;
}else{
return false;
}
}


/**
* Creates a nonce that is sent to the server (and the openID server MUST return).
* This number that should only ever exist once is needed for openID servers that run version 1.x of the openID
* specs. Returns an id that still has to be encrypted.
*
* @param username Just a string that is quite unique. This method will append a timestamp for optimal uniqueness.
*/
function CreateNonce($username) {

return $username . '' . time();
}

/**
* Since domain names are case insensitive, convert the host (domain) part to lowercase
* MUST have a scheme set, otherwise, the lowercasing WONT work!!
* (actually parse_url() wont have detected 'host'...)
*
* @return False if unable to parse
*
*/
function GlueURL($parsed)
{

if (! is_array($parsed)) return false;
$uri = isset($parsed['scheme']) ? $parsed['scheme'].':'.((strtolower($parsed['scheme']) == 'mailto') ? '':'//'): '';
$uri .= isset($parsed['user']) ? $parsed['user'].($parsed['pass']? ':'.$parsed['pass']:'').'@':'';

$uri .= isset($parsed['host']) ? strtolower($parsed['host']) : '';
$uri .= isset($parsed['port']) ? ':'.$parsed['port'] : '';
$uri .= isset($parsed['path']) ? $parsed['path'] : '';
$uri .= isset($parsed['query']) ? '?'.$parsed['query'] : '';
$uri .= isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';

return $uri;
}

function curl_redir_exec($ch)
{
static $curl_loops = 0;
static $curl_max_loops = 20;
if ($curl_loops++ >= $curl_max_loops)
{
$curl_loops = 0;
return FALSE;
}
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// was: $data = curl_exec($ch);
$response = curl_exec($ch);
// was: list($header, $data) = explode("\n\n", $data, 2);
list($header, $data) = explode("\n\n", $response, 2);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code == 301 || $http_code == 302)
{
$matches = array();
preg_match('/Location:(.*?)\n/', $header, $matches);
$url = @parse_url(trim(array_pop($matches)));
if (!$url)
{
//couldn't process the url to redirect to
$curl_loops = 0;
return $response; // was: return $data;
}
$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
if (!$url['scheme'])
$url['scheme'] = $last_url['scheme'];
if (!$url['host'])
$url['host'] = $last_url['host'];
if (!$url['path'])
$url['path'] = $last_url['path'];
$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
curl_setopt($ch, CURLOPT_URL, $new_url);
return $this->curl_redir_exec($ch);
} else {
$curl_loops=0;
return $response; // was: return $data;
}
}

}
?>
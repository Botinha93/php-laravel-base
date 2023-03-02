<?php
    namespace App\Http\Services;


class HttpReqProcessor{
        static $baseUrl = "";
        static $realm = '';
        static function setRealm(string $realm){
            HttpReqProcessor::$realm = $realm;
        }
        static function setBaseUrl(string $baseUrl){
            HttpReqProcessor::$baseUrl = $baseUrl;
        }
        static $previlousTimeinSeconds = 0;
        static $token = '';

        static function initRequest (string $url, $method, $body = ""){
            $request = new \HTTP_Request2();
            $request->setUrl( HttpReqProcessor::$baseUrl . '/admin/realms/' . HttpReqProcessor::$realm . $url);
            $request->setMethod($method);
            $request->setConfig(array(
            'follow_redirects' => TRUE
            ));
            $request->setHeader(array(
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer ' . HttpReqProcessor::$token
            ));
            if(!empty($body)){
                $request->setBody($body);
            }
            try {
                return $request->send();
            }catch(\HTTP_Request2_Exception $e) {
                $rtOBJ = new class {
                    public $url = "";
                    public function getStatus(){
                        return 404;
                    }
                    public function getReasonPhrase(){
                        return "URL não encontrada: " . $this->url ;
                    }
                };
                $rtOBJ ->url = $request->getUrl();
                return $rtOBJ;
            }
        }

        static function getToken(string $username, string $password, string $keycloakURL, string $realm){
            HttpReqProcessor::$baseUrl = $keycloakURL;
            HttpReqProcessor::$realm = $realm;
            if(HttpReqProcessor::$previlousTimeinSeconds == 0 && HttpReqProcessor::$previlousTimeinSeconds < time()){
                $request = new \HTTP_Request2();
                $request->setUrl(HttpReqProcessor::$baseUrl . '/realms/master/protocol/openid-connect/token');
                $request->setMethod(\HTTP_Request2::METHOD_POST);
                $request->setConfig(array(
                'follow_redirects' => TRUE
                ));
                $request->setHeader(array(
                'Content-Type' => 'application/x-www-form-urlencoded'
                ));
                $request->addPostParameter(array(
                'username' => $username,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => 'admin-cli'
                ));
                try {
                $response = $request->send();
                if ($response->getStatus() == 200) {
                    $jsonString = $response->getBody();
                    $bodyAsArray = json_decode($jsonString, true);
                    HttpReqProcessor::$token = $bodyAsArray['access_token'];
                    HttpReqProcessor::$previlousTimeinSeconds = time()  +$bodyAsArray['expires_in'];
                }
                else {
                    HttpReqProcessor::$token = '';
                    $previlousTimeinSeconds = 0;
                }
                }
                catch(\HTTP_Request2_Exception $e) {
                    HttpReqProcessor::$token = '';
                    HttpReqProcessor::$previlousTimeinSeconds = 0;
                }
            }
        }
    }
?>
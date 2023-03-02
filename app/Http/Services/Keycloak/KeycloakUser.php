<?php

    namespace App\Http\Services\Keycloak;

    use App\Http\Services\HttpReqProcessor;
    use App\Http\Services\StatusCode;

    class KeycloakUser{
      static public function tokenDecoder($token){
        $Array = explode('.', $token);
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . env('KEYCLOAK_PKEY') . "\n-----END PUBLIC KEY-----";
        $payload = utf8_decode($Array[0] . '.' . $Array[1]);
        $signature = base64_decode(strtr($Array[2], '-_', '+/'));
        global $UserInfo;
        $UserInfo =  json_decode(base64_decode($Array[1]), true);
        $message = "";
        if(count($Array) == 3){
          //if(openssl_verify($payload, $signature, $publicKey, "sha256WithRSAEncryption" ) == 1){
            if ($UserInfo['azp'] == env('KEYCLOAK_CLIENT')) {
              $returnObj = new StatusCode(200, "Token JWT é um token valido", json_decode(base64_decode($Array[1]),true));
              $returnObj['token'] = $token;
              return $returnObj;
            }else{
              $message = "Cliente informado no token não é o permitido";
            }
          //}else{
            $message = "Token não possui assinatura valida";
          //}
        }else{
          $message = "Token informado não é JWT";
        }
        $returnObj = StatusCode::$TOKEN_INVALIDO->setResult($message);
        $returnObj['token'] = $token;
        return $returnObj;
      }

      static public function validaUsuario($keycloakIssURL, $token){
        $request = new \HTTP_Request2();
        $request->setUrl($keycloakIssURL . '/protocol/openid-connect/userinfo');
        $request->setMethod(\HTTP_Request2::METHOD_GET);
        $request->setConfig(array(
          'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
          'Authorization' => 'Bearer ' . $token
        ));
        try {
          $response = $request->send();
          if ($response->getStatus() == 200) {
            $returnObj = StatusCode::$TOKEN_VALIDO->setResult(json_decode($response->getBody(), true));
          }else if ($response->getStatus() == 401){
            $stat = json_decode($response->getBody(), true);
            $returnObj = StatusCode::$TOKEN_INVALIDO->setResult($stat['error_description']);
          }else if ($response->getStatus() == 400){
            $stat = json_decode($response->getBody(), true);
            $returnObj = StatusCode::$TOKEN_INVALIDO->setResult($stat['error_description']);
          }
          else {
            $returnObj = StatusCode::$KEYCLOAK_INEXISTENTE->setResult('Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase());
          }
        }
        catch(\HTTP_Request2_Exception $e) {
          $returnObj = StatusCode::$GENERICO->setResult($e->getMessage());
        }
        $returnObj['token'] = $token;
        return $returnObj;
      }
      static public function loginUsuario($keycloakIssURL, $user, $pass, $client, $secret=null, $scope="profile openid"){
        $request = new \HTTP_Request2();
        $request->setUrl($keycloakIssURL . '/protocol/openid-connect/token');
        $request->setMethod(\HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
          'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
          'Content-Type' => 'application/x-www-form-urlencoded'
        ));
        $request->addPostParameter(array(
          'username' => $user,
          'password' => $pass,
          'grant_type' => 'password',
          'client_id' => $client,
          'client_secret' => ($secret==null ? '' : $secret),
          'scope' => $scope
        ));
        $returnObj['token'] = '';
        try {
          $response = $request->send();
          if ($response->getStatus() == 200) {
            $token = (json_decode($response->getBody(), true))['access_token'];
            $returnObj = StatusCode::$TOKEN_VALIDO->setResult(keycloakUser::validaUsuario($keycloakIssURL , $returnObj["token"])["result"]);
            $returnObj["token"] = $token;
          }
          else if ($response->getStatus() == 400){
            $stat = json_decode($response->getBody(), true);
            if($stat['error'] == 'invalid_client'){
              $returnObj = StatusCode::$CLIENTE_INVALIDO;
            }else if($stat['error'] == 'invalid_scope'){
              $returnObj = StatusCode::$ESCOPO_INVALIDO;
            }else if($stat['error'] == 'unauthorized_client'){
              $returnObj = StatusCode::$ACESSODIRETO_INVALIDO;
            }else{
              $returnObj = StatusCode::$GENERICO_400;
            }
            $returnObj["result"] = $stat['error_description'];
          }
          else if ($response->getStatus() == 401){
            $stat = json_decode($response->getBody(), true);
            if($stat['error'] == 'invalid_grant'){
              $returnObj = StatusCode::$USUARIO_INVALIDO;
            }else if($stat['error'] == 'unauthorized_client'){
              $returnObj = StatusCode::$SEGREDO_INVALIDO;
            }else{
              $returnObj = StatusCode::$GENERICO_401;
            }
            $returnObj["result"] = $stat['error_description'];
          }else{
            $stat = json_decode($response->getBody(), true);
            $returnObj = StatusCode::$GENERICO->setResult($stat['error'] . ' : ' .  json_encode($stat));
          }
        }
        catch(\HTTP_Request2_Exception $e) {
          $returnObj = StatusCode::$GENERICO->setResult($e->getMessage());
        }
        return $returnObj;
      }
    }
?>
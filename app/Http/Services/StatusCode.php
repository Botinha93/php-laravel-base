<?php

namespace App\Http\Services;

class StatusCode extends \ArrayObject
{
    public static $TOKEN_VALIDO;
    public static $TOKEN_INVALIDO;
    public static $ENDPOINT_ERRADO;
    public static $KEYCLOAK_INEXISTENTE;
    public static $CLIENTE_INVALIDO;
    public static $ESCOPO_INVALIDO;
    public static $USUARIO_INVALIDO;
    public static $SEGREDO_INVALIDO;
    public static $ACESSODIRETO_INVALIDO;
    public static $GENERICO_400;
    public static $GENERICO_401;
    public static $GENERICO_404;
    public static $GENERICO_501;
    public static $GENERICO;
    public static $GENERICO_200;
    public static $GENERICO_201;
    public static $GENERICO_206;
    public static $INATIVO_401;

    public function __construct($code = 200, $response = "Pedido valido e informações processadas", $result = ""){
        $this['status']['code'] = $code;
        $this['status']['response'] = $response;
        $this['result'] = $result;
    }
    public function setResult($result){
        return new StatusCode($this['status']['code'], $this['status']['response'], $result);
    }
    public static function init(){
        StatusCode::$GENERICO_200 = new StatusCode();
        StatusCode::$GENERICO_201 = new StatusCode(201, "Resultados processados, recursos criados");
        StatusCode::$GENERICO_206 = new StatusCode(206, "Range de dados processado, verifique saida para resultados individuais");
        StatusCode::$TOKEN_VALIDO = new StatusCode(200, "Token valido e usuario retornado");
        StatusCode::$TOKEN_INVALIDO = new StatusCode(412, "Token verificado não é valido ou token não enviado como Bearer");
        StatusCode::$ENDPOINT_ERRADO = new StatusCode(404, "O endpoint de informações de usuario não foi encontrado");
        StatusCode::$KEYCLOAK_INEXISTENTE = new StatusCode(422, "A url não contem uma instancia do keycloak");
        StatusCode::$CLIENTE_INVALIDO = new StatusCode(422, "Cliente requer consentimento do usuario ou ClientID invalido");
        StatusCode::$ESCOPO_INVALIDO = new StatusCode(502, "Um dos scopes fornecidos não é valido para este cliente");
        StatusCode::$USUARIO_INVALIDO = new StatusCode(401, "Usuario ou senha errados");
        StatusCode::$SEGREDO_INVALIDO = new StatusCode(401, "Segredo do cliente invalido");
        StatusCode::$ACESSODIRETO_INVALIDO = new StatusCode(401, "Cliente não autorizado para acesso direto");
        StatusCode::$GENERICO_400 = new StatusCode(400, "Requisição mal formada, favor checar os dados");
        StatusCode::$GENERICO_401 = new StatusCode(401, "Não autorizado o acesso ao recurso");
        StatusCode::$INATIVO_401 = new StatusCode(401, "Recurso inativo");
        StatusCode::$GENERICO_404 = new StatusCode(404, "Não encontrado");
        StatusCode::$GENERICO_501 = new StatusCode(501, "Operação não suportada");
        StatusCode::$GENERICO = new StatusCode(502, "Erro não tratado, verifique diretamente o retorno");
    }
}
StatusCode::init();

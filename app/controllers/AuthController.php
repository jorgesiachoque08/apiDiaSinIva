<?php
use Phalcon\Mvc\Controller;
class AuthController extends Controller {

  
    public function verificarToken(){
        
        $token = "4bcd18b5-ac96-46ad-9135-fe440020c59f";
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
            list($jwt) = sscanf( $headers['Authorization'], 'Bearer %s');
            if($jwt == $token){
                return true;
            }else{
                $codigo = 403;
                $this->response->setJsonContent(array(
                    "code"=>$codigo,
                    "mensaje"=>"Forbidden"
                ));
            }
        }else{
            $codigo = 401;
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "mensaje"=>"Unauthorized"
            ));
        }
        

        $this->response->setStatusCode($codigo);
        $this->response->send();

    }
}

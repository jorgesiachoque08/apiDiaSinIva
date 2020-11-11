<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;

use \Phalcon\Http\Response;

class Validador {
    protected $requeridos;
    protected $msjCamposRequerid;
    protected $enteros;
    protected $msjCamposEnteros;
    protected $email;
    protected $msjCamposEmail;
    protected $fechas;
    protected $msjCamposFechas;
    protected $regex;
    protected $msjCamposRegex;
    protected $length;
    protected $msjCamposLength;


    public function Validando($params)
    {
        $validador = new Validation();
        $response = new Response();
        $messagesRetorno = [];
        
        
        if(isset($this->requeridos) && isset($this->msjCamposRequerid)){
            $this->validandoRequeridos($validador);
        }

        if(isset($this->enteros) && isset($this->msjCamposEnteros)){
            $this->validandoEnteros($validador);
        }

        if(isset($this->email) && isset($this->msjCamposEmail)){
            $this->validandoEmails($validador);
        }

        if(isset($this->fechas) && isset($this->msjCamposFechas)){
            $this->validandoFechas($validador);
        }

        if(isset($this->regex) && isset($this->msjCamposRegex)){
            $this->validandoRegex($validador);
        }
        if(isset($this->length) && isset($this->msjCamposLength)){
            $this->validandoLength($validador);
        }
        

        try {
            $messages = $validador->validate($params);

            if (count($messages)) {
                foreach ($messages as $message) {
                    
                    $messagesRetorno[$message->getField()][] = $message->getMessage();
                }
               $response->setJsonContent(array(
                    "code"=>404,
                    "mensaje"=>$messagesRetorno
                ));
               $response->setStatusCode(404);
               $response->send();
            }else{
                return true;
            }
        } catch (Exception $e) {
           $response->setJsonContent(array(
                "code"=>500,
                "mensaje"=>"Error en el servidor"
            ));
           $response->setStatusCode(404);
           $response->send();
        }
    }

    public function validandoRequeridos($validador)
    {
        $validador->add(
            $this->getRequeridos(),
            new PresenceOf(
                $this->getMsjCamposRequerid()
            )
        );
    }

    public function validandoEnteros($validador)
    {
        $validador->add(
            $this->getEnteros(),
            new DigitValidator(
                $this->getMsjCamposEnteros()
            )
        );
    }
    public function validandoEmails($validador)
    {
        $validador->add(
            $this->getEmail(),
            new Email(
                $this->getMsjCamposEmail()
            )
        );
    }

    public function validandoFechas($validador)
    {
        $validador->add(
            $this->getFechas(),
            new DateValidator(
                $this->getMsjCamposFechas()
            )
        );
    }

    public function validandoRegex($validador)
    {
        $validador->add(
            $this->getRegex(),
            new Regex(
                $this->getMsjCamposRegex()
            )
        );
    }
    public function validandoLength($validador)
    {
        $validador->add(
            $this->getLength(),
            new StringLength(
                $this->getMsjCamposLength()
            )
        );
    }

    public function setRequeridos($requeridos)
    {
       $this->requeridos = $requeridos;
    }

    public function setMsjCamposRequerid($msjCamposRequerid)
    {
        $this->msjCamposRequerid = $msjCamposRequerid;
    }

    public function getRequeridos()
    {
        return $this->requeridos;
    }

    public function getMsjCamposRequerid()
    {
        return $this->msjCamposRequerid;
    }

    public function setEnteros($enteros)
    {
       $this->enteros = $enteros;
    }

    public function setMsjCamposEnteros($msjCamposEnteros)
    {
        $this->msjCamposEnteros = $msjCamposEnteros;
    }

    public function getEnteros()
    {
        return $this->enteros;
    }

    public function getMsjCamposEnteros()
    {
        return $this->msjCamposEnteros;
    }

    public function setEmail($email)
    {
       $this->email = $email;
    }

    public function setMsjCamposEmail($msjCamposEmail)
    {
        $this->msjCamposEmail = $msjCamposEmail;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMsjCamposEmail()
    {
        return $this->msjCamposEmail;
    }

    public function setFechas($fechas)
    {
       $this->fechas = $fechas;
    }

    public function setMsjCamposFechas($msjCamposFechas)
    {
        $this->msjCamposFechas = $msjCamposFechas;
    }

    public function getFechas()
    {
        return $this->fechas;
    }

    public function getMsjCamposFechas()
    {
        return $this->msjCamposFechas;
    }

    public function setRegex($regex)
    {
       $this->regex = $regex;
    }

    public function setMsjCamposRegex($msjCamposRegex)
    {
        $this->msjCamposRegex = $msjCamposRegex;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    public function getMsjCamposRegex()
    {
        return $this->msjCamposRegex;
    }

    public function setLength($length)
    {
       $this->length = $length;
    }

    public function setMsjCamposLength($msjCamposLength)
    {
        $this->msjCamposLength = $msjCamposLength;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getMsjCamposLength()
    {
        return $this->msjCamposLength;
    }
}
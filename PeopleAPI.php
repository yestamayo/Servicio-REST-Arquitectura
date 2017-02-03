<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PeopleAPI
 *
 * @author yesmith.tamayo
 */

require_once "PagoDB.php";

class PeopleAPI {    
    public function API(){
        header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            $this->getPagos();
            break;     
        case 'POST'://inserta
            $this->savePago();
            break;                
        case 'PUT'://actualiza
            $this->updatePago();
            break;      
        case 'DELETE'://elimina
            $this->deletePago();
            break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }
    }
    
    /**
    * Respuesta al cliente
    * @param int $code Codigo de respuesta HTTP
    * @param String $status indica el estado de la respuesta puede ser "success" o "error"
    * @param String $message Descripcion de lo ocurrido
    */
    function response($code=200, $status="", $message="") {
       http_response_code($code);
       if( !empty($status) && !empty($message) ){
           $response = array("status" => $status ,"message"=>$message);  
           echo json_encode($response,JSON_PRETTY_PRINT);    
       }            
    }  
    
    /**
    * función que segun el valor de "action" e "id":
    *  - mostrara una array con todos los registros de personas
    *  - mostrara un solo registro 
    *  - mostrara un array vacio
    */
    function getPagos(){
        if($_GET['action']=='pagos'){         
            $db = new PagoDB();
            if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID                 
                $response = $db->getPago($_GET['id']);                
                echo json_encode($response,JSON_PRETTY_PRINT);
            }else{ //muestra todos los registros                   
                $response = $db->getPagos();              
                echo json_encode($response,JSON_PRETTY_PRINT);
            }
        }else{
               $this->response(400);
        }       
    }  

    /**
    * metodo para guardar un nuevo registro de pago en la base de datos
    */
    function savePago(){
       if($_GET['action']=='pagos'){   
            //Decodifica un string de JSON
            $obj = json_decode( file_get_contents('php://input') );   
            $objArr = (array)$obj;
            if (empty($objArr)){
               $this->response(422,"error","Nada para agregar. Verifique los datos json");                           
            }else if(isset($obj->cedula, $obj->montopago, $obj->metodopago)){
                $pago = new PagoDB();     
                $pago->insert( $obj->cedula, $obj->montopago, $obj->metodopago );
                $this->response(200,"success","Nuevo registro guardado");                             
            }else{
                $this->response(422,"error","Propiedad no definida");
            }
        } else{               
            $this->response(400);
        }  
    }
    
    /**
    * Actualiza un recurso
    */
    function updatePago() {
       if( isset($_GET['action']) && isset($_GET['id']) ){
           if($_GET['action']=='pagos'){
               $obj = json_decode( file_get_contents('php://input') );   
               $objArr = (array)$obj;
               if (empty($objArr)){                        
                   $this->response(422,"error","Nothing to add. Check json");                        
               }else if(isset($obj->name)){
                   $db = new PagoDB();
                   $db->update($_GET['id'], $obj->name);
                   $this->response(200,"success","Record updated");                             
               }else{
                   $this->response(422,"error","The property is not defined");                        
               }     
               exit;
          }
       }
       $this->response(400);
    }
    
    /**
     * elimina persona
     */
    function deletePago(){
        if( isset($_GET['action']) && isset($_GET['id']) ){
            if($_GET['action']=='pagos'){                   
                $db = new PagoDB();
                $db->delete($_GET['id']);
                $this->response(204);                   
                exit;
            }
        }
        $this->response(400);
    }

}//end class
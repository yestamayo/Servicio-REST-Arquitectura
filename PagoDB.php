<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PagoDB
 *
 * @author yesmith.tamayo
 */
class PagoDB {
    
    protected $mysqli;
    const LOCALHOST = '127.0.0.1';
    const USER = 'root';
    const PASSWORD = 'root';
    const DATABASE = 'arq_soft_pasarela_pagos';
    
    /**
     * Constructor de clase
     */
    public function __construct() {           
        try{
            //conexión a base de datos
            $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
        }catch (mysqli_sql_exception $e){
            //Si no se puede realizar la conexión
            http_response_code(500);
            exit;
        }     
    } 
    
    /**
     * obtiene un solo registro dado su ID
     * @param int $id identificador unico de registro
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getPago($id=0){      
        $stmt = $this->mysqli->prepare("SELECT * FROM pagos WHERE cedula=? ; ");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();        
        $pagos = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $pagos;              
    }
    
    /**
     * obtiene todos los registros de la tabla "pagos"
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getPagos(){        
        $result = $this->mysqli->query('SELECT * FROM pagos');          
        $pagos = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $pagos; 
    }
    
    /**
     * añade un nuevo registro en la tabla pagos
     * @param String $cedula nombre completo de persona
     * @return bool TRUE|FALSE 
     */
    public function insert($cedula='', $montopago='', $metodopago=''){
        $stmt = $this->mysqli->prepare("INSERT INTO pagos(cedula,montopago,metodopago,fecha) VALUES (?,?,?,?); ");
        $stmt->bind_param('ssss', $cedula,$montopago,$metodopago,date("Y-m-d H:i:s"));
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;        
    }
    
    /**
     * elimina un registro dado el ID
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function delete($id=0) {
        $stmt = $this->mysqli->prepare("DELETE FROM pagos WHERE id = ? ; ");
        $stmt->bind_param('s', $id);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;
    }
    
    /**
     * Actualiza registro dado su ID
     * @param int $id Description
     */
    public function update($id, $newName) {
        if($this->checkID($id)){
            $stmt = $this->mysqli->prepare("UPDATE pagos SET name=? WHERE id = ? ; ");
            $stmt->bind_param('ss', $newName,$id);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;    
        }
        return false;
    }
    
    /**
     * verifica si un ID existe
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function checkID($id){
        $stmt = $this->mysqli->prepare("SELECT * FROM pagos WHERE ID=?");
        $stmt->bind_param("s", $id);
        if($stmt->execute()){
            $stmt->store_result();    
            if ($stmt->num_rows == 1){                
                return true;
            }
        }        
        return false;
    }
    
}
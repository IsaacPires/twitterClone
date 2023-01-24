<?php

namespace App\Models;
use MF\Model\Model;

class Usuario extends Model{

  private $id;
  private $nome;
  private $email;
  private $senha;

  public function __get($atributo)
  {
    return $this->$atributo;
  }

  public function __set($atributo, $valor)
  {
    $this->$atributo = $valor;
  }

  public function save(){
    $query = "insert into usuarios(nome, email, senha) values(:nome, :email, :senha)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':nome', $this->__get('nome'));
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha'));
    $stmt->execute();
  }

  public function validaCadastro(){
    $valido = true;

    if(strlen($this->__get('nome'))<= 3){
      $valido = false;
    }
    if(strlen($this->__get('email'))<= 3){
      $valido = false;
    }
    if(strlen($this->__get('senha'))<= 5){
      $valido = false;
    }
    return $valido;
  }

  public function validaEmailUnico(){
    $query = "select nome, email from usuarios where email = :email";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function autenticar(){
    $query = "select id, nome, email from usuarios where email = :email and senha = :senha";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha'));
    $stmt->execute();
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if($user['id'] && $user['nome']){
      $this->id = $user['id'];
      $this->nome = $user['nome'];
    }
    
    return $this;
  }
}
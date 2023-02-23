<?php

namespace App\Models;
use MF\Model\Model;

class Usuario extends Model{

  private $id;
  private $idUser;
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

  public function searchFollow(){ 
    $query = "
    select u.id, u.nome, u.email, 
    (
      select
       count(*)
      from
       usuarios_seguidores as us
      where
       us.id_usuario = :idAtual
       and us.id_usuario_seguindo = u.id
    ) as seguindo_sn
    from  
      usuarios as u
    where 
      u.nome like :nome and u.id != :idAtual";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
    $stmt->bindValue(':idAtual', $this->id);
    $stmt->execute();
    $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $user;
  }

  public function countTweets(){    
    $query = 'SELECT COUNT(id_usuario) AS tweets FROM tweets where id_usuario = :id';
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $user;
  }

  public function countSeguindo(){    
    $query = 'SELECT COUNT(id_usuario) AS seguindo FROM usuarios_seguidores where id_usuario = :id';
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $user;
  }

  public function countSeguidores(){    
    $query = 'SELECT COUNT(id_usuario_seguindo) AS seguidores FROM usuarios_seguidores where id_usuario_seguindo = :id';
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id', $this->id);
    $stmt->execute();
    $user = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $user;
  }

}
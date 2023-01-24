<?php

namespace App\Models;
use MF\Model\Model;

class Tweets extends Model{

  private $id;
  private $id_usuario;
  private $tweet;
  private $date;

  public function __get($atributo)
  {
    return $this->$atributo;
  }

  public function __set($atributo, $valor)
  {
    $this->$atributo = $valor;
  }

  public function save(){
    $query = "insert into tweets(id_usuario, tweet) values(:id_usuario, :tweet)";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindValue(':tweet', $this->__get('tweet'));
    $stmt->execute();
    return $this;
  }

  public function getAll(){
    $query = "
    select t.id, t.id_usuario, u.nome, t.tweet, t.date 
    from tweets as t
    left join Usuarios as u
    on t.id_usuario = u.id
    where id_usuario = :id_usuario";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
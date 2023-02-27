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
    left join usuarios as u
    on t.id_usuario = u.id
    where 
    t.id_usuario = :id_usuario 
    or
    t.id_usuario in (
    select id_usuario_seguindo 
    from usuarios_seguidores 
    where id_usuario = :id_usuario 
    )
    order by 
    t.date desc";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getByPage($limit, $offset){
    $query = "
    select t.id, t.id_usuario, u.nome, t.tweet, t.date 
    from tweets as t
    left join usuarios as u
    on t.id_usuario = u.id
    where 
    t.id_usuario = :id_usuario 
    or
    t.id_usuario in (
    select id_usuario_seguindo 
    from usuarios_seguidores 
    where id_usuario = :id_usuario 
    )
    order by 
      t.date desc
    limit
      {$limit}
    offset
      {$offset}
    
    ";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function delete(){
    $query = 'Delete from Tweets where id = :id';
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id', $this->__get('tweet'));
    $stmt->execute();
    return $this;
  }

}
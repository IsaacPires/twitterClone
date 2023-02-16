<?php

namespace App\Models;
use MF\Model\Model;

class usuarios_seguidores extends Model{

  private $id;
  private $id_usuario;
  private $id_usuario_seguindo;

  public function __get($atributo)
  {
    return $this->$atributo;
  }

  public function __set($atributo, $valor)
  {
    $this->$atributo = $valor;
  }

  public function follow(){
    $query = "
    insert into usuarios_seguidores(id_usuario, id_usuario_seguindo)
    values(:id_usuario, :id_usuario_seguindo)";

    $stmt = $this->db->prepare($query);
    $stmt->bindvalue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindvalue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
    $stmt->execute();
  }

  public function unfollow(){
    $query = "
    DELETE FROM usuarios_seguidores
    WHERE id_usuario_seguindo = :id_usuario_seguindo 
    and id_usuario = :id_usuario";

    $stmt = $this->db->prepare($query);
    $stmt->bindvalue(':id_usuario', $this->__get('id_usuario'));
    $stmt->bindvalue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
    $stmt->execute();
  }
}
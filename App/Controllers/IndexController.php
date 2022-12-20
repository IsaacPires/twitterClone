<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class indexController extends Action{

  public function index(){

    $this->render('index');

  }

  public function inscreverse(){
    
    $this->view->erroCadastro = false;

    $this->render('inscreverse');

  }

  public function registrar(){

    $usuario = Container::getModel('Usuario');

    $usuario->__set('nome', $_POST['name']);
    $usuario->__set('email', $_POST['email']);
    $usuario->__set('senha', $_POST['password']);

    if($usuario->validaCadastro() && count($usuario->validaEmailUnico()) == 0){
      
        $usuario->save();
        $this->render('cadastro');
      
    }else{

      $this->view->erroCadastro = true;

      $this->render('inscreverse');
    }


  }

}
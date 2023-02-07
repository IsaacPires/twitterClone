<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{
  
  public function timeline(){

    $this->verificaSession();
    
    $tweet = Container::getModel('Tweets');
    $tweet->__set('id_usuario', $_SESSION['id']);
    $tweets = $tweet->getAll();
    $this->view->tweets = $tweets;
    $this->render('timeline');
  }

  public function tweet(){

    $this->verificaSession();

    $tweet = Container::getModel('Tweets');
    $tweet->__set('id_usuario', $_SESSION['id']);
    $tweet->__set('tweet', $_POST['tweet']);
    $tweet->save();
    header('LOCATION: \timeline');
  }

  public function quemSeguir(){ 

    $this->verificaSession();

    $this->view->usuarioAtual = $_SESSION['nome'];

    $pesquisa = $_GET['pesquisa']; 
    $result = array();
    if(isset($pesquisa) && $pesquisa != ''){
      $searchResult = Container::getModel('Usuario');
      $searchResult->__set('id', $_SESSION['id']);
      $searchResult->__set('nome', $pesquisa);
      $result = $searchResult->searchFollow();
    }

    $this->view->seguir = $result;
    $this->render('quemSeguir');


  }

  private function verificaSession(){

    session_start();

    if(!$_SESSION['id'] || !$_SESSION['nome']):
      header('LOCATION: \?login=error');
    else:
      return true;
    endif;
  }

}
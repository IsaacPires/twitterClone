<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{
  
  public function timeline(){

    $this->verificaSession();
    $this->count();
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
      $_SESSION['pesquisa'] = $pesquisa;
      $result = $searchResult->searchFollow();
    }
    
    $this->view->seguir = $result;
    $this->render('quemSeguir');
  }

  public function acao(){
    $this->verificaSession();
    $action = isset($_GET['acao']) ? $_GET['acao']: '';
    $id_user = isset($_GET['id_user']) ? $_GET['id_user']: '';
    $user = Container::getModel('usuarios_seguidores');
    $user->__set('id_usuario', $_SESSION['id']);
    if($action == 'seguir'):
      $user->__set('id_usuario_seguindo', $id_user);
      $user->follow();

    elseif( $action == 'deixar_seguir'):
      $user->__set('id_usuario_seguindo', $id_user);
      $user->unfollow();
    endif;
    $manterPesquisa = $_SESSION['pesquisa'];
    header('LOCATION:\quem_seguir?pesquisa='.$manterPesquisa);
    $this->count();
  }

  public function deleteTweet(){

    $this->verificaSession();

    $tweet = Container::getModel('Tweets');
    $tweet->__set('tweet', $_GET['deletetweet']);
    $tweet->delete();
    header('LOCATION: \timeline');
  }

  private function count(){
    $this->verificaSession();

    $user = Container::getModel('Usuario');
    $user->__set('id', $_SESSION['id']);
    $countTweet = $user->countTweets();
    $countSeguindo = $user->countSeguindo();
    $countSeguidores = $user->countSeguidores();

    $_SESSION['countTweets'] = $countTweet[0]["tweets"];
    $_SESSION['seguindo']    = $countSeguindo[0]["seguindo"];
    $_SESSION['seguidores']  = $countSeguidores[0]["seguidores"];
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
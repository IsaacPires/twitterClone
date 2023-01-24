<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{
  
  public function timeline(){

    session_start();

    if(!$_SESSION['id'] || !$_SESSION['nome']):
      header('LOCATION: \?login=error');
    else:


      $tweet = Container::getModel('Tweets');
      $tweet->__set('id_usuario', $_SESSION['id']);
      $tweets = $tweet->getAll();
      $this->view->tweets = $tweets;

      $this->render('timeline');
    endif;
  }

  public function tweet(){

    session_start();

    if(!$_SESSION['id'] || !$_SESSION['nome']):
      header('LOCATION: \?login=error');
    else:
      $tweet = Container::getModel('Tweets');

      $tweet->__set('id_usuario', $_SESSION['id']);
      $tweet->__set('tweet', $_POST['tweet']);
      $tweet->save();
      header('LOCATION: \timeline');
      
    endif;
  }
  

}
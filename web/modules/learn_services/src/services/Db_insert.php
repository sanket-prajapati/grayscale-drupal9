<?php 
namespace Drupal\learn_services\services;
use Drupal\Core\Database\Connection;

class Db_insert{
  protected $database;

  public function __construct(Connection $database){
    $this->database= $database;
  }

  /**
   * set Data function
   */
  public function setData($form_state){
    $this->database->insert('customForm')
      ->fields([
        'email' => $form_state ->getValue('email'),
        'name' => $form_state -> getValue('name'),
        'created' => time(),
        ])
      ->execute();
  }

  /**
  * get Data function
  */
  public function getData(){
    $results = $this->database->select('customForm', 'cf')
      ->fields('cf')
      ->execute()-> fetchAll();
    return $results;
  }


}
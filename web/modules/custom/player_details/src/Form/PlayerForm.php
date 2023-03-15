<?php

namespace Drupal\player_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Messanger\MessengerTrait;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PlayerForm extends FormBase{
  public function getFormId(){
    return 'create_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state){
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => '',
      '#required' => true,
      '#attributes' => [
        'placeholder' => 'Enter Your Name',
      ],
    ];

    $form['gender'] = [
      '#type' => 'select',
      '#title' => t('Gender'),
      '#required' => true,
        '#options' => [
          'male' => $this->t('Male'),
          'female' => $this -> t('Female'),
          'other' => $this -> t('Other'),
        ],
        // '#default_value' => $this->getSetting('gender'),
    ];

    $form['phone'] = [
      '#type' => 'number',
      '#title' => t('Phone'),
      '#required' => true,
      '#default_value' => '',
      '#attributes' => [
        'placeholder' => 'Enter Your phone'
      ]
    ];

    $form['address'] = [
      '#type' => 'textarea',
      '#title' => t('Address'),
      '#required' => true,
      '#default_value' => '',
      '#attribites' =>[
          'placeholder' => 'Enter Your address',
      ],

    ];

    $form['save'] = [
      '#type' => 'submit',
      '#value' => 'Register Now',
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state){
    $name = $form_state-> getValue('name');
    $phone=$form_state->getValue('phone');
    if (trim($name)==''){
      $form_state-> setErrorByName('name', $this->t('Name is required'));
    }
    elseif(!preg_match("/^[a-zA-Z ]*$/",$name)){
      $form_state->setErrorByName('name',$this->t('Please enter valid name'));
    }
    elseif($phone==NULL){
      $form_state-> setErrorByName('phone', $this->t('Phone is required'));
    }
    // elseif(!preg_match("/^[1-9][0-9]{10}$/", $phone) || !strlen((string)$phone)==10 ){
    // elseif(!preg_match("/^[1-9][0-9]{10}$/", $phone) ){
    elseif(!preg_match('/^[0-9]{10}+$/', $phone)){
      $form_state->setErrorByName('phone', $this->t('Please enter 10 digit phone'));
    }
    elseif($form_state->getValue('address')==NULL){
      $form_state-> setErrorByName('address', $this->t('Address is required'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    $formData = $form_state->getValues() ;
    //this is demo code for queue
    // $data=13;
    // $data = [
    //   'id' => '13',
    //   '
    // ];
    // $result = \Drupal:: database()->query("SELECT [id] FROM {teams} WHERE [id] NOT IN (SELECT [team_id] FROM {players} WHERE [team_id] IS NOT NULL)")->fetchAllAssoc('id');
    $result = \Drupal:: database()->query("SELECT [id] FROM {teams} WHERE [id] NOT IN (SELECT [team_id] FROM {players} WHERE [team_id] IS NOT NULL)")->fetchAll();
    $data=$result;
    // dump($result);//works I got id's but make sure first see database table
    /**array:3 [▼
     * 0 => {#235 ▼
     *   +"id": "12"
     * }
     * 1 => {#611 ▼
     *   +"id": "13"
     * }
     * 2 => {#612 ▼
     *   +"id": "19"
     * }
     *]
    **/
    $queue = \Drupal::queue('team_cleaner');
    $queue->createQueue();
    $team_id = [];
    $i=0;
    foreach($result as $record){
      $team_id['id'][$i] = $record->id;
      $i++;
    }
    // $team_id = $data->id;
    $team->id = $team_id;
    // dump($team->id);//works
    /**
     * ^ array:1 [▼
     * "id" => array:3 [▼
     *   0 => "12"
     *   1 => "13"
     *   2 => "19"
     *  ]
     * ]
     */
    // now this is directly create queue of $data=$result and store in queue as std object you can see that in queue manager
    $queue->createItem($data);
    // dump($queue->createItem($data));//works
    // dump($team_id);//worsk
    // dump($queue);//works
    // exit;
    /* $team_id = $data;
     * $item = new \stdClass();
     * $item->id = $team_id;
     * $queue->createItem($item);
     * dump($queue);
     * exit;
    **/

    // print_r($formData);
    // exit;
      \Drupal:: database()-> insert('player')
        -> fields(['name','gender','phone','address'])
        -> values([
          $form_state -> getValue('name'),
          $form_state -> getValue('gender'),
          $form_state -> getValue('phone'),
          $form_state -> getValue('address'),
        ])
        -> execute();

    $name = $form_state->getValue('name');


    \Drupal::Messenger()-> addMessage(t("$name your form is submitted successfully"));
  }
}
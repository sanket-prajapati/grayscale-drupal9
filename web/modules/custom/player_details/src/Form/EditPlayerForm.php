<?php

namespace Drupal\player_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Messanger\MessengerTrait;

class EditPlayerForm extends FormBase{

  public function getFormId(){
    return 'create_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state){
    $id= \Drupal:: routeMatch()->getParameter('id');

    $query = \Drupal::database()->select('player','p')
      ->fields('p')
      ->condition('id',$id);
    $results= $query->execute()->fetchAll();

    // echo '<pre>';
    // print_r($results);
    // exit;

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#default_value' => $results[0]->name,
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
        '#default_value' => $results[0]->gender,
    ];

    $form['phone'] = [
      '#type' => 'number',
      '#title' => t('Phone'),
      '#required' => true,
      '#default_value' => $results[0]->phone,
      '#attributes' => [
        'placeholder' => 'Enter Your phone'
      ]
    ];

    $form['address'] = [
      '#type' => 'textarea',
      '#title' => t('Address'),
      '#required' => true,
      '#default_value' => $results[0]->address,
      '#attribites' =>[
          'placeholder' => 'Enter Your address',
      ],

    ];

    $form['update'] = [
      '#type' => 'submit',
      '#value' => 'Update Now',
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
    elseif(!preg_match("/^([a-zA-Z' ]+)$/",$name)){
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
    
    $id= \Drupal:: routeMatch()->getParameter('id');

    $name= $form_state->getValue('name');
    $gender=$form_state->getValue('gender');
    $phone=$form_state->getValue('phone');
    $address=$form_state->getValue('address');

    \Drupal:: database()->update('player')
      ->fields(['name'=>$name, 'gender'=>$gender, 'phone'=>$phone, 'address'=>$address])
      ->condition('id',$id)
      ->execute();

    $response = new \Symfony\Component\HttpFoundation\RedirectResponse('../player-list') ;
    $response->send();
    // drupal_flush_all_caches(); 
    //For specific cache render
    \Drupal::service('cache.render')->invalidateAll();
    //If you want to clear specific cache like route cache then you can run the following code:
    \Drupal::service("router.builder")->rebuild();
    \Drupal::Messenger()-> addMessage(t("$name your form is updated successfully"));
  }
}
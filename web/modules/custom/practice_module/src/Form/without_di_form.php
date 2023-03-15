<?php
namespace Drupal\practice_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;

/** 
 * Implement form
 */
class without_di_form extends FormBase{
  /**
   * {@inheritdoc}
   */
  
  public function getFormId(){
    return 'form_without_di';
  }
  public function buildForm(array $form, FormStateInterface $form_state, $option=NULL ){
    $form['email']=[
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#default' => '',
      '#description' => 'Your email address',
      '#required' => TRUE,
    ];

    $form['action']['send'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];
    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state){
    // $value = $form_state->getValue('email');
    // if($value=!\Drupal::service('email.validator')->isValid($value)){
    //   $form_state->setErrorByName('email',t("Email address is not valid", array('%mail'=> $value)));
      // return;
    // }
  }
  public function submitForm(array &$form, FormStateInterface $form_state){
    // \Drupal::logger('practice_module')->error('This is my error message'); //not printed
    // \Drupal::service('logger.factory')->get('hello_world')->error('This is my error message');//nor works
    // \Drupal::logger('practice_module')->notice('@type: deleted %title.',array(
    //   '@type' => $this->entity->bundle(),
    //   '%title' => $this->entity->label(),
    // ));
    $current_user = \Drupal::currentUser();
    if($current_user){
      \Drupal::Messenger()-> addMessage(t("From submitted by login user"));
    }
    else{
      \Drupal::Messenger()->addMessage(t('From submitted by end user'));
    }
  }

}
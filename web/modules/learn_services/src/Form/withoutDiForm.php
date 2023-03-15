<?php
namespace Drupal\learn_services\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;

/** 
 * Implement form
 */
class withoutDiForm extends FormBase{
  protected $loaddata;
  /**
   * {@inheritdoc}
   */
  public function getFormId(){
    return 'form_without_di';
  }

  public function __construct(){
    $this->loaddata= \Drupal::service('learn_services.dbinsert');
  }
  public function buildForm(array $form, FormStateInterface $form_state, $option=NULL ){
    $form['email']=[
      '#type' => 'textfield',
      '#title' => t('Memeber Email'),
      '#default' => '',
      '#description' => 'Your email address',
      '#required' => TRUE,
    ];
    $form['name']=[
      '#type' => 'textfield',
      '#title' => t('Member Name'),
      '#default' => '',
      '#description' => 'Your name',
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    ];
    return $form;
  }
  // public function validateForm(array &$form, FormStateInterface $form_state){
  //   $value = $form_state->getValue('email');
  //   if($value=!\Drupal::service('email.validator')->isValid($value)){
  //     $form_state->setErrorByName('email',t("Email address is not valid", array('%mail'=> $value)));
  //     return;
  //   }
  // }
  public function submitForm(array &$form, FormStateInterface $form_state){
    // \Drupal::logger('learn_services')->error('This is my error message');
    // \Drupal::service('logger.factory')->get('learn_services')->error('This is my error message');//Not works
    // Logs a notice
    // \Drupal::logger('my_module')->notice($message);
    // Logs an error
    // \Drupal::logger('my_module')->error($message);
    // \Drupal::logger('my_module')->warning($message);
    $message='This is my message';
    // \Drupal::logger('learn_services')->alert($message);//not works
    $query=$this->loaddata->setData($form_state);
    \Drupal::Messenger()-> addMessage(t("Your From submitted is successfully"),'status');
  }

}
<?php
namespace Drupal\learn_services\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Configuration form definition for the salutation message.
 */
class SalutationConfigurationForm extends ConfigFormBase {
  /**
  * {@inheritdoc}
  */
  //There are two ways of loading configuration objects: for editing and for reading (immutable). With this method, we inform it that we want to edit that configuration item.
  protected function getEditableConfigNames() {
    return ['hello_world.custom_salutation'];
  }
  /**
  * {@inheritdoc}
  */
  public function getFormId() {
    return 'salutation_configuration_form';
  }
  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // we are using the config() method of the previously mentioned trait to load up our editable configuration object from the Drupal configuration factory 
    $config = $this->config('hello_world.custom_salutation');
    $form['salutation'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Salutation'),
      '#description' => $this->t('Please provide the salutation you want to use.'),
      '#default_value' => $config->get('salutation'),
    );
    return parent::buildForm($form, $form_state);
  }
  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $salutation = $form_state->getValue('salutation');
    if (strlen($salutation) > 20) {
      $form_state->setErrorByName('salutation', $this->t('This salutation is too long'));
    }
  }
  /**
  * {@inheritdoc}
  */
  //The last method we need is the submit handler, which basically loads up the editable configuration object, puts the submitted value in it, and then saves it.   
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('hello_world.custom_salutation')
      ->set('salutation', $form_state->getValue('salutation'))
      ->save();
      
      //Finally, it also calls the parent method, which then simply sends a success message to the user on the screen using the Messenger service—a standard way of showing the user a success or error message
      parent::submitForm($form, $form_state);
      
    $message = 'This is message comming from notice logger';
    
    // \Drupal::logger('learn_services')->info('The Hello World salutation has been changed to @message.', ['@message' => $form_state->getValue('salutation')]);
    \Drupal::logger('learn_services')->notice($message);//works
    \Drupal::logger('learn_services')->error('This is error by injected service2');//works
    // \Drupal::service('learn_services.channels_logger')->error('This is error by injected service2');//not works
    // \Drupal::service('myservice_logger_channels')->error('This is error by inject service2');//not works
    // Call to undefined method Drupal\learn_services\services\myLogger::error()

    // \Drupal::service('logger.factory')->get('learn_services')->error('This is my error message');//Works
  }
}
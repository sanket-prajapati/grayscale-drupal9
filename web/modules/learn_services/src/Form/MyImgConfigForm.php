<?php

namespace Drupal\learn_services\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MyImgConfigForm extends ConfigFormBase {
    protected function getEditableConfigNames() {
      return [
        'learn_services.img_settings'
      ];
    }
  
    public function getFormId() {
      return 'myimg_form';
    }
  
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('learn_services.img_settings');
      $form['my_file'] = [
        '#type' => 'managed_file',
        '#title' => 'my image file',
        '#name' => 'my_custom_file',
        '#description' => $this->t('my file description'),
        '#default_value' => $config->get('my_file'),
        '#upload_location' => 'public://'
      ];
  


      return parent::buildForm($form, $form_state);
    }
  
    public function validateForm(array &$form, FormStateInterface $form_state) {
      parent::validateForm($form, $form_state);
    }
  
    public function submitForm(array &$form, FormStateInterface $form_state) {
      // Check if image is uploaded.
      dump($form_state->getValue('my_file'));
      dump($form_state);
      exit;
      if(isset($form_state['default_press_release_image'])) {
        // Set file status to permanent.
        $image = $form_state->getValue('my_file');
        $file = File::load($image[0]);
        $file->setPermanent();
        $file->save();
        // Add to file usage calculation.
        \Drupal::service('file.usage')->add;
      }
  
      $this->config('learn_services.img_settings')
        ->set('default_press_release_image', $form_state->getValue('default_press_release_image'))
        ->save();
  
      parent::submitForm($form, $form_state);
    }
  }

//   $this->config('hello_world.custom_salutation')
//   ->set('salutation', $form_state->getValue('salutation'))
//   ->save();
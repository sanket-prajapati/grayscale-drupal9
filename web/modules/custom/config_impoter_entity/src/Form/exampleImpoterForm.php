<?php

namespace Drupal\config_impoter_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class exampleImpoterForm.
 */
class exampleImpoterForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $example_impoter = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $example_impoter->label(),
      '#description' => $this->t("Label for the Example impoter."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $example_impoter->id(),
      '#machine_name' => [
        'exists' => '\Drupal\config_impoter_entity\Entity\exampleImpoter::load',
      ],
      '#disabled' => !$example_impoter->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $example_impoter = $this->entity;
    $status = $example_impoter->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Example impoter.', [
          '%label' => $example_impoter->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Example impoter.', [
          '%label' => $example_impoter->label(),
        ]));
    }
    $form_state->setRedirectUrl($example_impoter->toUrl('collection'));
  }

}

<?php

namespace Drupal\productentity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ProductsTypeForm.
 */
class ProductsTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $products_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $products_type->label(),
      '#description' => $this->t("Label for the Product list here type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $products_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\productentity\Entity\ProductsType::load',
      ],
      '#disabled' => !$products_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $products_type = $this->entity;
    $status = $products_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Product list here type.', [
          '%label' => $products_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Product list here type.', [
          '%label' => $products_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($products_type->toUrl('collection'));
  }

}

<?php

namespace Drupal\productentity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Product list here entity.
 *
 * @see \Drupal\productentity\Entity\Products.
 */
class ProductsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\productentity\Entity\ProductsInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished product list here entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published product list here entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit product list here entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete product list here entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add product list here entities');
  }


}

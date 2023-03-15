<?php

namespace Drupal\productentity;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\productentity\Entity\ProductsInterface;

/**
 * Defines the storage handler class for Product list here entities.
 *
 * This extends the base storage class, adding required special handling for
 * Product list here entities.
 *
 * @ingroup productentity
 */
interface ProductsStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Product list here revision IDs for a specific Product list here.
   *
   * @param \Drupal\productentity\Entity\ProductsInterface $entity
   *   The Product list here entity.
   *
   * @return int[]
   *   Product list here revision IDs (in ascending order).
   */
  public function revisionIds(ProductsInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Product list here author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Product list here revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\productentity\Entity\ProductsInterface $entity
   *   The Product list here entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ProductsInterface $entity);

  /**
   * Unsets the language for all Product list here with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}

<?php

namespace Drupal\productentity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Product list here entities.
 *
 * @ingroup productentity
 */
interface ProductsInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Product list here name.
   *
   * @return string
   *   Name of the Product list here.
   */
  public function getName();

  /**
   * Sets the Product list here name.
   *
   * @param string $name
   *   The Product list here name.
   *
   * @return \Drupal\productentity\Entity\ProductsInterface
   *   The called Product list here entity.
   */
  public function setName($name);

  /**
   * Gets the Product list here creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Product list here.
   */
  public function getCreatedTime();

  /**
   * Sets the Product list here creation timestamp.
   *
   * @param int $timestamp
   *   The Product list here creation timestamp.
   *
   * @return \Drupal\productentity\Entity\ProductsInterface
   *   The called Product list here entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Product list here revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Product list here revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\productentity\Entity\ProductsInterface
   *   The called Product list here entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Product list here revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Product list here revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\productentity\Entity\ProductsInterface
   *   The called Product list here entity.
   */
  public function setRevisionUserId($uid);

}

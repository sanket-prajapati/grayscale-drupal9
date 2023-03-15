<?php

namespace Drupal\config_impoter_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Example impoter entity.
 *
 * @ConfigEntityType(
 *   id = "example_impoter",
 *   label = @Translation("Example impoter"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\config_impoter_entity\exampleImpoterListBuilder",
 *     "form" = {
 *       "add" = "Drupal\config_impoter_entity\Form\exampleImpoterForm",
 *       "edit" = "Drupal\config_impoter_entity\Form\exampleImpoterForm",
 *       "delete" = "Drupal\config_impoter_entity\Form\exampleImpoterDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\config_impoter_entity\exampleImpoterHtmlRouteProvider",
 *     },
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   config_prefix = "example_impoter",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/example_impoter/{example_impoter}",
 *     "add-form" = "/admin/structure/example_impoter/add",
 *     "edit-form" = "/admin/structure/example_impoter/{example_impoter}/edit",
 *     "delete-form" = "/admin/structure/example_impoter/{example_impoter}/delete",
 *     "collection" = "/admin/structure/example_impoter"
 *   }
 * )
 */
class exampleImpoter extends ConfigEntityBase implements exampleImpoterInterface {

  /**
   * The Example impoter ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Example impoter label.
   *
   * @var string
   */
  protected $label;

}

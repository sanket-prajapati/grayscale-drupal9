<?php

namespace Drupal\productentity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Product list here type entity.
 *
 * @ConfigEntityType(
 *   id = "products_type",
 *   label = @Translation("Product list here type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\productentity\ProductsTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\productentity\Form\ProductsTypeForm",
 *       "edit" = "Drupal\productentity\Form\ProductsTypeForm",
 *       "delete" = "Drupal\productentity\Form\ProductsTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\productentity\ProductsTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   config_prefix = "products_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "products",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/products_type/{products_type}",
 *     "add-form" = "/admin/structure/products_type/add",
 *     "edit-form" = "/admin/structure/products_type/{products_type}/edit",
 *     "delete-form" = "/admin/structure/products_type/{products_type}/delete",
 *     "collection" = "/admin/structure/products_type"
 *   }
 * )
 */
class ProductsType extends ConfigEntityBundleBase implements ProductsTypeInterface {

  /**
   * The Product list here type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Product list here type label.
   *
   * @var string
   */
  protected $label;

}

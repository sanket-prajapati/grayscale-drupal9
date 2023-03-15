<?php

/**
 * @file
 * Contains products.page.inc.
 *
 * Page callback for Product list here entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Product list here templates.
 *
 * Default template: products.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_products(array &$variables) {
  // Fetch Products Entity Object.
  $products = $variables['elements']['#products'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

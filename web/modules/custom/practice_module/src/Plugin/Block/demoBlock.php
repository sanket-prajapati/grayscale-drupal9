<?php
/**
 * @file
 * Contains \Drupal\practice_module\Plugin\Block\TestBlock.
 */

namespace Drupal\practice_module\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides 'my custom' block.
 *
 * @Block(
 *   id = "my_custom_block",
 *   admin_label = @Translation("My Custom Block"),
 *   category = @Translation("System"),
 * )
 */
class demoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array('#markup' => 'hello world');
  }

}
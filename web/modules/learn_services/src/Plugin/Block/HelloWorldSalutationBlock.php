<?php
namespace Drupal\learn_services\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\learn_services\HelloWorldSalutation;

use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Hello World Salutation block.
 * 
 * @Block(
 *   id = "hello_world_salutation_block",
 *   admin_label= @Translation("Hello world salutation"),
 * )
 */
class HelloWorldSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface{
  /**
   * The salutation service.
   *
   * @var \Drupal\hello_world\HelloWorldSalutation
  */
  protected $salutation;
  /**
  * Constructs a HelloWorldSalutationBlock.
  */
  public function __construct(array $configuration, $plugin_id,$plugin_definition, HelloWorldSalutation $salutation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->salutation = $salutation;
  }
  /**
  * {@inheritdoc}
  */
  public static function create(ContainerInterface $container,array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('hello_world.salutation')
    );
  }
  /**
  * {@inheritdoc}
  */
  // public function build() {
  //   return [
  //     '#markup' => $this->salutation->getSalutation(),
  //   ];
  // }

  public function build() {
    $build = [];
    $build[] = [
      '#theme' => 'container',
      '#children' => [
        '#markup' => $this->salutation->getSalutation(),
    ]
    ];
    $url = Url::fromRoute('learn_services.hide_block');
    // $project_link = Link::fromTextAndUrl(t('Remove'), $url);
    // $project_link = $project_link->toRenderable();
    // // $url->setOption('attributes', ['class' => 'use-ajax']);

    // $project_link['#attributes'] = array('class' => array('button', 'button-action', 'button--primary', 'button--small', 'use-ajax'));
    // print render($project_link);//works
    // print_r(render($project_link));
    // $path_btn_link=render($project_link);

    $build[] = [
      '#type' => 'link',
      '#url' => $url,
      // '#url' => $project_link,
      '#title' => $this->t('Remove'),
    ];
    return $build;
  }
}



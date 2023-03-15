<?php
namespace Drupal\javascript_accordion\Controller;
use Drupal\Core\Controller\ControllerBase;

class javascriptExample extends ControllerBase{
  public function javascript_demo(){
    $build['myElement'] = [
      '#theme' => 'javascript-example',
      '#title' => 'Javascript accordion demo',
    ];

    $build['myElement']['#attached']['library'][] = 'javascript_accordion.accordion';

    return $build;
  }
}


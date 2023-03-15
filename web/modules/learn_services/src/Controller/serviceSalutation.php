<?php

namespace Drupal\learn_services\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;  


class serviceSalutation extends ControllerBase {
//   /**
//   * @var \Drupal\Core\Config\ConfigFactoryInterface
//   */
  protected $configFactory;
  /*** HelloWorldSalutation constructor.
  *
  * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
  */
//   public function __construct(ConfigFactoryInterface $config_factory) {
//     $this->configFactory = $config_factory;
//   }

  public function getFactoryData(){
    $config = \Drupal::config('hello_world.custom_salutation');
    //    dump($config);
    $salutation = $config->get('salutation');
    if ($salutation !== "" && $salutation) {
      return [
        '#markup'=>$salutation,
    ];
    }
  }

}



?>

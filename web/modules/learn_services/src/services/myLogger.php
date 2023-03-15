<?php
// namespace Drupal\learn_services\services;
// use Drupal\Core\Logger\LoggerChannelFactoryInterface;

// class myLogger {
//     public function __construct(LoggerChannelFactoryInterface $factory) {
//       $this->loggerFactory = $factory;
//     }
  
//     public function doStuff($message) {
//       // Logs a notice to "my_module" channel.
//       $this->loggerFactory->get('learn_services')->notice($message);
//        // Logs an error to "my_other_module" channel.
//     //   $this->loggerFactory->get('my_other_module')->error($message);
//       $this->loggerFactory->get('learn_services')->error($message);
//     }
//   }
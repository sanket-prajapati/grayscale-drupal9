<?php
namespace Drupal\learn_services\services;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelloWorldSalutation {
    /**
     * @var \Drupal\learn_services\services\HelloWorldSalutation
     */
    protected $salutation;
    /**
     * HelloWorldController constructor.
     *
     * @param \Drupal\learn_services\services\HelloWorldSalutation $salutation
     */
    public function __construct(HelloWorldSalutation $salutation) {
     $this->salutation = $salutation;
    }
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
     return new static(
       $container->get('hello_world.salutation')
     );
    }

} 
<?php
/**
 * @file
 * Contains \Drupal\practice_module\Plugin\Block\diFormBlock.
 */
namespace Drupal\practice_module\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provide a 'DI form' Block.
 * @Block(
 *   id = "di_form_block",
 *   admin_label= @Translation("DI form Block"),
 *   category = @Translation("DI form Block"),
 * )
 */


// class diFormBlock extends BlockBase implements ContainerFactoryPluginInterface{
//     protected $formBuilder;
//     public function __contstruct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder){
//         parent::__construct($configuration, $plugin_id, $plugin_definition);
//         $this->formBuilder=$formBuilder;
//     }
//     public static function create(ContainerInterface $container,array $configuration, $plugin_id, $plugin_definition){
//         return new static (
//             $configuration,
//             $plugin_id,
//             $plugin_definition,
//             // Load the service required to construct this class.
//             $container->get('form_builder'),
//         );
//     } 
//     /**
//     * {@inheritdoc}
//     */
//     public function build(){
//         // $form=$this->formBuilder->getForm('Drupal\practice_module\Form\withoutDiForm');
//         $form = \Drupal:: formBuilder()-> getForm('Drupal\practice_module\Form\withoutDiForm');
//         return $form;
        
//     // return array('#markup' => 'hello world');
//     }

// }

class diFormBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a HelloBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // Instantiate this block class.
    return new static($configuration, $plugin_id, $plugin_definition,
      // Load the service required to construct this class.
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return $this->formBuilder->getForm('Drupal\practice_module\Form\withoutDiForm');
  }

}


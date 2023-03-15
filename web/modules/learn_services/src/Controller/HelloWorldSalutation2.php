<?php
namespace Drupal\learn_services\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\learn_services\HelloWorldSalutation;

use Drupal\Core\Url;
use Drupal\Core\Link;
// use Drupal\Core\Routing\Access\AccessInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RemoveCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; 

use Symfony\Component\HttpFoundation\RedirectResponse;

class HelloWorldSalutation2 extends ControllerBase implements ContainerInjectionInterface{
  /**
  * @var \Drupal\learn_services\HelloWorldSalutation
  */
  protected $salutation;
  /**
   * HelloWorldController constructor.
   *
   * @param \Drupal\learn_services\HelloWorldSalutation $salutation
   */
  public function __construct(HelloWorldSalutation $salutation) {
    // Make object from HelloWorldSalution and 
    // Then given to Private property(i.e, object)
    $this->salutation = $salutation;
    // We use this object in subsequence function and use object's class property and method
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    // Injecting service 'hello_world.salutation' from services.yml file
    // This will call class HelloWorldSalutation
      $container->get('hello_world.salutation')
    );
  }
  public function getFromContoller(){
      return [
        // We use method of object of HelloWorldSalution 
        '#markup' => $this->salutation->getSalutation(),
       ];
  }
  public function demoLink(){
    // $url = Url::fromRoute('hello_world.hello');
    // $url = Url::fromRoute('learn_services.getFromContoller', array('node' => NID));
    $url = Url::fromRoute('learn_services.getFromContoller');
    $project_link = Link::fromTextAndUrl(t('Open Project'), $url);
    $project_link = $project_link->toRenderable();
    // $path_text_link=render($project_link);
    // If you need some attributes.
    $project_link['#attributes'] = array('class' => array('button', 'button-action', 'button--primary', 'button--small'));
    // print render($project_link);//works
    // print_r(render($project_link));
    $path_btn_link=render($project_link);

    // if ($project_link->access()) {
    // Do something.
    return [
      '#markup' => t("Hello How are you $path_btn_link"),
    ];
    // } 
  } 

  public function getWithLibrary(){
    $time = new \DateTime();
    $render['#target'] = $this->t('world'); 
    $render['#attached'] = [
      'library' => [
        'learn_services/hello_world_clock',
      ],
    ];
    return [
      '#markup' => t("Hello How are you"),
    ];
  }

    /**
   * Route callback for hiding the Salutation block.
   * Only works for Ajax calls.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function hideBlock(Request $request) {
    // // dump($request);
    // $response = new AjaxResponse();
    // // dump($response);
    // $command = new RemoveCommand('.block-hello-world');
    // // dump($command);
    // $response->addCommand($command);
    // // dump($response);

    // exit;
    // return [
    //   '#markup' => t("Hello How are you"),
    // ];
    // if (!$request->isXmlHttpRequest()) {
    //   throw new NotFoundHttpException();
    // }
    $response = new AjaxResponse();
    
    // $command = new RemoveCommand('.block-hello-world');
    // exit;
    $command = new RemoveCommand('.block-helloworldsalutation');
    $response->addCommand($command);
    // return new RedirectResponse(Drupal\Core\Url::fromUri('route_name')); 
    // return new RedirectResponse(\Drupal::url('<front>'))
    // $response = new RedirectResponse($path);
    // $response->send();

    // $response = new RedirectResponse($url->toString());
    $url2 = Url::fromRoute('<front>')->toString();//works but removing not works
    // $url2 = Url::fromUri('internal:/node/2'); 
    $response2 = new RedirectResponse($url2);
    return $response && $response2->send();

    // hello_world_salutation_block
  } 
}



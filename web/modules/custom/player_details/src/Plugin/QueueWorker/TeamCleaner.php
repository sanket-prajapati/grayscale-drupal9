<?php
namespace Drupal\player_details\Plugin\QueueWorker;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * A worker plugin that removes a team from the database.
 *
 * @QueueWorker(
 *   id = "team_cleaner",
 *   title = @Translation("Team Cleaner"),
 *   cron = {"time" = 10}
 * )
 */
class TeamCleaner extends QueueWorkerBase implements ContainerFactoryPluginInterface {
  /**
  * @var \Drupal\Core\Database\Connection
  */
  protected $database;
  /**
  * Constructs a TeamCleaner worker.
  * 
  * @param array $configuration
  * @param string $plugin_id
  * @param mixed $plugin_definition
  * @param \Drupal\Core\Database\Connection $database
  */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $database;
 }
 /**
 * {@inheritdoc}
 */
  public static function create(ContainerInterface $container, array $configuration,$plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }
   /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // dump($data);
    // exit;
    $id = isset($data->id) && $data->id ? $data->id : NULL;
    // $id=$data->id;
 
    // $result = $this->database->query("SELECT [id] FROM {teams} WHERE [id] NOT IN (SELECT [team_id] FROM {players} WHERE [team_id] IS NOT NULL)")->fetchAllAssoc('id');

    // dump($id);
    // exit;
    if (!$id) {
      // throw new \Exception('Missing team ID');
      $message= 'Missing team ID';
      \Drupal::logger('player_details')->notice($message);
      return;
    } 
    // if (!$result) {
    //     throw new \Exception('Missing team ID');
    //     return;
    // } 
    $this->database->delete('teams')
      ->condition('id', $id)
      ->execute();
    \Drupal::logger('player_details')->notice('Success in deletion of record');
  }
} 
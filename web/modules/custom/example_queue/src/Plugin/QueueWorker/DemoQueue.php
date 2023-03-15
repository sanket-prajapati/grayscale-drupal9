<?php

namespace Drupal\example_queue\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Plugin implementation of the demo_queue queueworker.
 *
 * @QueueWorker (
 *   id = "demo_queue",
 *   title = @Translation("This is queue for database operation"),
 *   cron = {"time" = 30}
 * )
 */
class DemoQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // Process item operations.
  }

}

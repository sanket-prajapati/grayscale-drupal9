<?php

namespace Drupal\practice_module\Plugin\Importer;

use Drupal\csv_importer\Plugin\ImporterBase;

/**
 * Class BlockImporter.
 *
 * @Importer(
 *   id = "block_content_importer",
 *   entity_type = "block_content",
 *   label = @Translation("Block content importer")
 * )
 */
class BlockImporter extends ImporterBase {}
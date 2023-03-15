<?php

namespace Drupal\productentity\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\productentity\Entity\ProductsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProductsController.
 *
 *  Returns responses for Product list here routes.
 */
class ProductsController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Product list here revision.
   *
   * @param int $products_revision
   *   The Product list here revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($products_revision) {
    $products = $this->entityTypeManager()->getStorage('products')
      ->loadRevision($products_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('products');

    return $view_builder->view($products);
  }

  /**
   * Page title callback for a Product list here revision.
   *
   * @param int $products_revision
   *   The Product list here revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($products_revision) {
    $products = $this->entityTypeManager()->getStorage('products')
      ->loadRevision($products_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $products->label(),
      '%date' => $this->dateFormatter->format($products->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Product list here.
   *
   * @param \Drupal\productentity\Entity\ProductsInterface $products
   *   A Product list here object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ProductsInterface $products) {
    $account = $this->currentUser();
    $products_storage = $this->entityTypeManager()->getStorage('products');

    $langcode = $products->language()->getId();
    $langname = $products->language()->getName();
    $languages = $products->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $products->label()]) : $this->t('Revisions for %title', ['%title' => $products->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all product list here revisions") || $account->hasPermission('administer product list here entities')));
    $delete_permission = (($account->hasPermission("delete all product list here revisions") || $account->hasPermission('administer product list here entities')));

    $rows = [];

    $vids = $products_storage->revisionIds($products);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\productentity\Entity\ProductsInterface $revision */
      $revision = $products_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $products->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.products.revision', [
            'products' => $products->id(),
            'products_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $products->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.products.translation_revert', [
                'products' => $products->id(),
                'products_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.products.revision_revert', [
                'products' => $products->id(),
                'products_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.products.revision_delete', [
                'products' => $products->id(),
                'products_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['products_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}

<?php
/**
 * @File
 * Contains \Drupal\sitelocationtime\Plugin\BLock\UserLocationBlock
 */

namespace Drupal\sitelocationtime\Plugin\BLock;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Cache\Cache;
use Drupal\sitelocationtime\GetCurrentTime;

/**
 * User Location Salutation block.
 *
 * @Block(
 *  id = "user_location_block",
 *  admin_label = @Translation("User Location Block"),
 * )
 */
 
class UserLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $current_time;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, GetCurrentTime $current_time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->current_time = $current_time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('sitelocationtime.get_current_time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $location_details = $this->current_time->getCurrentTime();
	
    return [
      '#theme' => 'site_location_time',
      '#country' => $location_details['country'],
      '#city' => $location_details['city'],
      '#cur_date_time' => $location_details['formatted_time'],
      '#cache' => [
        'tags' => [
          'SITE_LOCATION_TIME_TAG'
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), [
      'config:siteLocationTime.adminsettings',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }
}
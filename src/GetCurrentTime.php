<?php
/**
 * @file
 * Contains \Drupal\sitelocationtime\GetCurrentTIme
 */

namespace Drupal\sitelocationtime;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Datetime\Time;
use Drupal\Core\Datetime\DateFormatter;

class GetCurrentTIme {
  protected $configFactory;
  protected $currentTime;
  protected $formattedTime;

  public function __construct(ConfigFactoryInterface $configFactory, Time $currentTime, DateFormatter $formattedTime) {
    $this->configFactory = $configFactory;
    $this->currentTime = $currentTime;
    $this->formattedTime = $formattedTime;
  }

  /**
   * Returns the Current Time based on Timezone
   */
  public function getCurrentTime() {
    $config = $this->configFactory->get('siteLocationTime.adminsettings');
    $country = $config->get('country');
    $city = $config->get('city');
    $timezone = $config->get('timezone');
    $current_time = $this->currentTime->getCurrentTime();
    $type = 'custom';
    $format = 'jS M Y - g:i A';
	$formatted_time = $this->formattedTime->format($current_time, $type, $format, $timezone, $langcode = NULL,);
    return array(
      'country' => $country,
      'city' => $city,
      'formatted_time' => $formatted_time,
    );
  }
}	
<?php
/**
 * @file
 * Contains \Drupal\sitelocationtime\Form\getSiteLocationForm
 */

namespace Drupal\sitelocationtime\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class getSiteLocationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'siteLocationTime.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'get_site_location_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('siteLocationTime.adminsettings');
    $timezone_options = static::getTimezoneDropdownOptions();

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Please enter country.'),
      '#default_value' => $config->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#description' => $this->t('Please enter city.'),
      '#default_value' => $config->get('city'),
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => $timezone_options,
      '#description' => $this->t('Please select timezone.'),
      '#default_value' => $config->get('timezone'),
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('siteLocationTime.adminsettings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
    \Drupal\Core\Cache\Cache::invalidateTags(array('SITE_LOCATION_TIME_TAG'));
    parent::submitForm($form, $form_state);
  }

  /**
   * Get the dropdown options for timezone field.
   */
  public static function getTimezoneDropdownOptions() {
    return [
      'America/Chicago' => 'America/Chicago',
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
	  'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
	  'Europe/London' => 'Europe/London',
    ];
  }

}
<?php

require_once 'actiontracker.civix.php';
use CRM_Actiontracker_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function actiontracker_civicrm_config(&$config) {
  _actiontracker_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function actiontracker_civicrm_install() {
  _actiontracker_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function actiontracker_civicrm_enable() {
  _actiontracker_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_alterMailParams().
 *
 * Adds tracking parameters to checksum URLs.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterMailParams/
 */
function actiontracker_civicrm_alterMailParams(&$params, $context) {
  if ($context != 'flexmailer') {
    return;
  }

  // Example: b.94.172.33cb5f41a0534f64@example.org
  if (preg_match('/\w+\.\d+\.\d+\.([a-zA-Z0-9]+)/', $params['Return-Path'], $matches)) {
    $dao = CRM_Core_DAO::executeQuery('SELECT q.id, j.mailing_id FROM civicrm_mailing_event_queue q LEFT JOIN civicrm_mailing_job j ON (j.id = q.job_id) WHERE q.hash = %1', [
      1 => [$matches[1], 'String'],
    ]);

    if ($dao->fetch()) {
      $params['text'] = CRM_Actiontracker_HtmlClickTracker::addTrackingCode($params['text'], $dao->mailing_id, $dao->id);
      $params['html'] = CRM_Actiontracker_HtmlClickTracker::addTrackingCode($params['html'], $dao->mailing_id, $dao->id);
    }
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function actiontracker_civicrm_buildForm($formName, &$form) {
  $queue_id = CRM_Utils_Request::retrieveValue('qid', 'Integer');
  $url_id = CRM_Utils_Request::retrieveValue('u', 'Integer');

  if (!$queue_id || !$url_id) {
    return;
  }

  $url = CRM_Mailing_Event_BAO_TrackableURLOpen::track($queue_id, $url_id);
}

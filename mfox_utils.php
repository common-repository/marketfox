<?php

function mfox_custom_cron_schedules($schedules){
  if(!isset($schedules["15min"])){
    $schedules["15min"] = array(
      'interval' => 15*60, //15*60
      'display' => __('Once every 15 minutes'));
  }
  if(!isset($schedules["30min"])){
    $schedules["30min"] = array(
      'interval' => 30*60,
      'display' => __('Once every 30 minutes'));
  }
  return $schedules;
}
add_filter('cron_schedules','mfox_custom_cron_schedules');


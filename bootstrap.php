<?php
include 'vendor/autoload.php';

if(file_exists('config.php')) {
    include 'config.php';
}

if(!defined('MILIGHT_WIFI_BRIDGE_IP')) {
    define('MILIGHT_WIFI_BRIDGE_IP', '192.168.2.63');
}
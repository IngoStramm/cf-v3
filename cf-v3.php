<?php

/**
 * Plugin Name: ConverteFacil Admin
 * Plugin URI: https://agencialaf.com
 * Description: Este plugin é parte integrante do ConverteFácil.
 * Version: 3.0.2
 * Author: Ingo Stramm
 * Text Domain: cfv3
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('CFV3_DIR', plugin_dir_path(__FILE__));
define('CFV3_URL', plugin_dir_url(__FILE__));

require_once 'cf-v3-functions.php';
require_once 'cf-v3-admin/cf-v3-admin.php';
require_once 'cf-v3-cmb.php';
require_once 'cf-v3-roles-capabilities.php';
require_once 'cf-v3-protect-admin-user.php';
require_once 'cf-v3-scripts.php';
require_once 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/cf-v3/master/info.json',
    __FILE__,
    'cf-v3'
);

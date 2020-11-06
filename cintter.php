<?php

/**
 * Plugin Name: Cintter
 * Plugin URI: https://agencialaf.com
 * Description: Este plugin é parte integrante do ConverteFácil.
 * Version: 1.0.0
 * Author: Ingo Stramm
 * Text Domain: CTT
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('CTT_DIR', plugin_dir_path(__FILE__));
define('CTT_URL', plugin_dir_url(__FILE__));

function ctt_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

// Add Shortcode
add_shortcode('ctt-product-btn', 'ctt_product_btn');

function ctt_product_btn()
{
    $post_id = get_the_ID();
    if (!$post_id) return 'ID não encontrado.';

    $href = get_permalink($post_id);
    $output = '<a href="' . $href . '" class="elementor-image-box-description">COMPRAR &gt;</a>';
    return $output;
}


require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/cintter/master/info.json',
    __FILE__,
    'cintter'
);

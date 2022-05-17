<?php

add_action('admin_enqueue_scripts', 'cfv3_add_script');

/**
 * Adiciona os scripts ao WordPress
 * 
 * cfv3_add_script
 *
 * @return void
 */
function cfv3_add_script()
{
    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    // Remover quando for para produção
    // if (empty($min)) :
    //     wp_enqueue_script('cf-v3-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    // endif;


    // Não é necessário
    // As cores são definidas pelo esquema de cores do usuário
    // wp_enqueue_style('cf-v3-admin-style', CFV3_URL . 'assets/css/cf-v3-admin-style.css', array(), '1.0');

    wp_register_script('cf-v3-script', CFV3_URL . 'assets/js/cf-v3' . $min . '.js', array(), '1.0.0', true);

    wp_enqueue_script('cf-v3-script');

    wp_localize_script('cf-v3-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

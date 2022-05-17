<?php

add_action('init', 'cfv3_is_not_an_administrator_admin_dashboard');

/**
 * cfv3_is_not_an_administrator_admin_dashboard
 *
 * @return void
 */
function cfv3_is_not_an_administrator_admin_dashboard()
{
    if (!cfv3_is_not_administrador())
        return;

    add_action('wp_dashboard_setup', 'cfa_admin_dashboard_widgets');

    /**
     * 
     * Dashboard: Customiza os Widgets
     * 
     * cfa_admin_dashboard_widgets
     *
     * @return void
     */
    function cfa_admin_dashboard_widgets()
    {
        cfv3_remove_widgets();
    }

    add_action('wp_dashboard_setup', 'cfv3_add_admin_widgets');

    /**
     * Adiciona os widgets do Painel Converte Fácil
     * 
     * cfv3_add_admin_widgets
     *
     * @return void
     */
    function cfv3_add_admin_widgets()
    {
        cfv3_get_admin_widgets();
    }
}

/**
 * Remove os widgets padrão do WordPress e de alguns plugins
 * 
 * cfv3_remove_widgets
 *
 * @return void
 */
function cfv3_remove_widgets()
{
    global $wp_meta_boxes;

    // Agora
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    // Elementor
    unset($wp_meta_boxes['dashboard']['normal']['core']['e-dashboard-overview']);
    // Samrt Crawl
    unset($wp_meta_boxes['dashboard']['normal']['core']['wds_sitemaps_dashboard_widget']);
    // Postagem rápida
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    // Novidades WP
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    // Atividades
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    // Health Check
    unset($wp_meta_boxes['dashboard']['normal']['core']['health_check_status']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
    // Booking
    unset($wp_meta_boxes['dashboard']['normal']['core']['booking_dashboard_widget']);
    // Woocommerce Avaliações Recentes
    remove_meta_box('woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');
    // Woocommerce Status
    remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
    // Yoast
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('yith_dashboard_products_news', 'dashboard', 'normal');
    remove_meta_box('yith_dashboard_blog_news', 'dashboard', 'normal');
}

/**
 * Pega os widgets do Painel CF
 * @link: https://painel.convertefacil.com.br
 * 
 * cfv3_get_admin_widgets
 *
 * @return void
 */
function cfv3_get_admin_widgets()
{
    // $start_time = microtime(true);
    if (false === ($widgets = get_transient('cf_panel_widgets'))) {
        $xml = simplexml_load_file("https://painel.convertefacil.com.br/feed/?post_type=widget");

        if (!$xml)
            return add_meta_box('cf-widget-default', __('Suporte Exclusivo', 'cfv3'), function () {
                $content = '
                <img alt="' . __('Suporte Exclusivo', 'cfv3') . '" src="' . CFV3_URL . '/assets/images/suporte-exclusivo.jpg" style="width: 100%; height: auto; display: block; margin: auto;" />
                ';
                $content .= '<p>Precisa de ajuda? Você tem suporte exclusivo com o Converte Fácil de Segunda a Sexta das 10 às 16h.</p>';
                echo $content;
            }, 'dashboard', 'normal', 'high');

        $widgets = [];
        foreach ($xml->children() as $node_1) {
            foreach ($node_1->item as $node_2) {
                $widgets[(int)$node_2->menu_order] = array(
                    'title' => (string)$node_2->title,
                    'content' => trim((string)$node_2->content),
                    'thumb' => (string)$node_2->thumb,
                    'position' => (string)$node_2->position
                );
            }
        }
        ksort($widgets);
        set_transient('cf_panel_widgets', $widgets, HOUR_IN_SECONDS);
    }
    // $end_time = microtime(true);
    // $run_time = ($end_time - $start_time);
    // cfv3_debug($run_time);

    foreach ($widgets as $k => $widget) :
        $content = '<img src="' . $widget['thumb'] .  '" alt="' . $widget['title'] . '" style="width: 100%; height: auto; display: block; margin: auto;" />';
        $content .= $widget['content'];
        add_meta_box('cf-widget-' . $k, $widget['title'], function () use ($content) {
            echo $content;
        }, 'dashboard', $widget['position'], 'high');
    endforeach;
}

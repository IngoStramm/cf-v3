<?php

/**
 * 
 * Remove itens do admin bar
 * 
 * cfv3_remove_admin_bar_menu_items
 *
 * @param  object $wp_admin_bar
 * @return void
 */
function cfv3_remove_admin_bar_menu_items($wp_admin_bar)
{
    if (!cfv3_is_not_administrador())
        return;

    $cfv3_comments_status = get_default_comment_status();
    if ($cfv3_comments_status !== 'open')
        $wp_admin_bar->remove_node('comments');


    $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('customize');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('wds_wizard');
    $wp_admin_bar->remove_node('disqus');
    $wp_admin_bar->remove_node('about');
    $wp_admin_bar->remove_node('wporg');
    $wp_admin_bar->remove_node('documentation');
    $wp_admin_bar->remove_node('support-forums');
    $wp_admin_bar->remove_node('feedback');
    $wp_admin_bar->remove_node('elementor_app_site_editor');
}

add_action('admin_bar_menu', 'cfv3_remove_admin_bar_menu_items', 999);

/**
 * cfv3_elementor_admin_bar
 *
 * @param  array $admin_bar_config
 * @return array
 */
function cfv3_elementor_admin_bar($admin_bar_config)
{
    if (cfv3_is_not_administrador())
        return;

    return $admin_bar_config;
}

add_filter('elementor/frontend/admin_bar/settings', 'cfv3_elementor_admin_bar');

/**
 *
 * Altera o botão de limpar o cache do WP Rocket no admin bar,
 * para só exibir o botão e não o "node pai"
 * 
 *  cfv3_change_wp_rocket_admin_bar_button
 *
 * @param  object $wp_admin_bar
 * @return void
 */
function cfv3_change_wp_rocket_admin_bar_button($wp_admin_bar)
{
    if (!cfv3_is_not_administrador())
        return;

    if (!function_exists('rocket_load_textdomain'))
        return;

    $new_node = $wp_admin_bar->get_node('purge-all');
    $wp_admin_bar->remove_node('wp-rocket');
    $clear_wp_rocket_cache_node = array(
        'id'        => 'cf-wp-rocket',
        'title'        => $new_node->title,
        'href'        => $new_node->href,
    );
    $wp_admin_bar->add_node($clear_wp_rocket_cache_node);
}

add_action('admin_bar_menu', 'cfv3_change_wp_rocket_admin_bar_button', PHP_INT_MAX - 1);

/**
 * 
 * Botão para limpar o cache do Hummingbird (Legado)
 * O botão só é adicionado, se o Hummingbird estiver ativo
 * 
 * cfv3_admin_bar_hummingbird_clear_cache_button
 *
 * @param  class $admin_bar
 * @return void
 */
function cfv3_admin_bar_hummingbird_clear_cache_button($wp_admin_bar)
{
    if (!cfv3_is_not_administrador())
        return;

    if (!class_exists('Hummingbird\\WP_Hummingbird'))
        return;

    $wp_admin_bar->add_menu(array(
        'id'    => 'cfv3-clear-cache',
        'parent' => null,
        'group'  => null,
        'title' => __('Limpar cache', 'cfv3'), //you can use img tag with image link. it will show the image icon Instead of the title.
        'href'  => '#',
        // 'meta' => [
        //     'title' => __('Limpar cache do site', 'cfv3'), //This title will show on hover
        // ]
    ));
}

add_action('admin_bar_menu', 'cfv3_admin_bar_hummingbird_clear_cache_button', 500);

/**
 * 
 * Esconde via CSS o avatar se o WordPress estiver configurado para não exibir um avatar
 * 
 * cfv3_admin_bar_hide_empty_avatar
 *
 * @return void
 */
function cfv3_admin_bar_hide_empty_avatar()
{
    if (!cfv3_is_not_administrador())
        return;

?>
    <style>
        #wpadminbar #wp-admin-bar-my-account.with-avatar>.ab-empty-item img,
        #wpadminbar #wp-admin-bar-my-account.with-avatar>a img,
        #wp-admin-bar-user-info .avatar {
            display: none;
        }

        #wpadminbar #wp-admin-bar-my-account.with-avatar #wp-admin-bar-user-actions>li {
            margin-left: 16px !important;
        }
    </style>
<?php
}

add_action('wp_head', 'cfv3_admin_bar_hide_empty_avatar');
add_action('admin_head', 'cfv3_admin_bar_hide_empty_avatar');

<?php

add_action('init', 'cfv3_is_not_an_administrator_admin_bar');

function cfv3_is_not_an_administrator_admin_bar()
{
    if (!cfv3_is_not_administrador())
        return;


    add_action('admin_bar_menu', 'cfv3_remove_admin_bar_menu_items', 999);

    add_action('admin_bar_menu', 'cfv3_admin_bar_hummingbird_clear_cache_button', 500);

    add_action('wp_head', 'cfv3_admin_bar_hide_empty_avatar');
    add_action('admin_head', 'cfv3_admin_bar_hide_empty_avatar');
}

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
    $wp_admin_bar->remove_node('wds_wizard');
    $wp_admin_bar->remove_node('disqus');
}

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
function cfv3_admin_bar_hummingbird_clear_cache_button(WP_Admin_Bar $admin_bar)
{
    if (!class_exists('Hummingbird\\WP_Hummingbird'))
        return;

    $admin_bar->add_menu(array(
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

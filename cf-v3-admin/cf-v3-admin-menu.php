<?php

add_action('init', 'cfv3_is_not_an_administrator_admin_menu');

function cfv3_is_not_an_administrator_admin_menu()
{
    if (!cfv3_is_not_administrador())
        return;

    add_action('admin_menu', 'cfv3_remove_menu_items', 99);

    add_action('admin_menu', 'cfv3_rename_menu_items', 99);

    add_action('admin_menu', 'cfv3_add_menu_items', 99);
}

add_action('init', 'cfv3_is_not_an_administrator_and_not_an_editor_admin_menu');

/**
 * 
 * Remove o Contact Form 7 para os usuários que não sejam "administrador" e "editor"
 * (Legado)
 * 
 * cfv3_is_not_an_administrator_and_not_an_editor_admin_menu
 *
 * @return void
 */
function cfv3_is_not_an_administrator_and_not_an_editor_admin_menu()
{
    if (!cfv3_is_not_administrador() || !cfv3_is_not_editor())
        return;

    add_action('admin_menu', 'cfv3_remove_contact_form7_admin_menu_item');
}

/**
 * 
 * Remove itens do Menus do sidebar
 * 
 * cfv3_remove_menu_items
 *
 * @return void
 */
function cfv3_remove_menu_items()
{
    global $menu, $submenu;
    // cfv3_debug($menu);
    add_menu_page(__('Editar Menus'), __(' Menus '), 'edit_theme_options', 'nav-menus.php', null, 'dashicons-menu', 60);

    //Elementor
    remove_menu_page('edit.php?post_type=elementor_library');
    //Appearance
    remove_menu_page('themes.php');
    //Tools
    remove_menu_page('tools.php');
    remove_menu_page('wds_wizard');
    //blocks (Flatsome/Legado)
    remove_menu_page('edit.php?post_type=blocks');
    remove_menu_page('qligg');
    remove_menu_page('disqus');
    //Crocoblock
    remove_menu_page('jet-dashboard');
    //Smart Filters
    remove_menu_page('edit.php?post_type=jet-smart-filters');
    //Jet Engine
    remove_menu_page('jet-engine');
    //Segurança WP
    remove_menu_page('aiowpsec');

    // Woocommerce
    remove_submenu_page('woocommerce', 'wc-settings');
    remove_submenu_page('woocommerce', 'wc-status');
    remove_submenu_page('woocommerce', 'wc-addons');

    // Envato Elements
    remove_submenu_page('envato-elements', 'envato-elements#/welcome');
    remove_submenu_page('envato-elements', 'envato-elements#/settings');
    remove_submenu_page('envato-elements', 'envato-elements#/template-kits/free-kits');
    remove_submenu_page('envato-elements', 'envato-elements#/template-kits/free-blocks');
    remove_submenu_page('envato-elements', 'envato-elements#/template-kits/installed-kits');

    $cfv3_comments_status = get_default_comment_status();
    if ($cfv3_comments_status !== 'open')
        remove_menu_page('edit-comments.php');
}

/**
 * 
 * Renomeia Menus do sidebar
 * 
 * cfv3_rename_menu_items
 *
 * @return void
 */
function cfv3_rename_menu_items()
{
    global $menu, $submenu;
    foreach ($menu as $k => $item) :

        if ($item[2] == 'index.php') :

            $menu[$k][0] = __('Dashboard Converte Fácil', 'cf_v3');

        elseif ($item[1] == 'upload_files') :

            $menu[$k][0] = __('Biblioteca de Arquivos', 'cf_v3');

        elseif ($item[1] == 'edit_pages' && $item[0] == 'Páginas') :

            $menu[$k][0] = __('Páginas do Site e Landing Pages', 'cf_v3');

        elseif ($item[2] == 'edit-comments.php') :

            $menu[$k][0] = __('Moderar Comentários', 'cf_v3');

        elseif ($item[2] == 'wpcf7') :

            $menu[$k][0] = __('Formulários de Contato', 'cf_v3');

        elseif ($item[2] == 'envato-elements') :

            $menu[$k][0] = __('Temas e Banco de Imagem', 'cf_v3');

        elseif ($item[1] == 'edit_posts' && $item[0] == 'Posts') :

            $menu[$k][0] = __('Conteúdo e SEO Inteligente', 'cf_v3');

        endif;
    endforeach;

    if (isset($submenu['envato-elements'][0]) && isset($submenu['envato-elements'][1])) :
        $submenu['envato-elements'][0] = $submenu['envato-elements'][1]; // substitui oa URL
    endif;

    if (isset($submenu['envato-elements'][5]))
        $submenu['envato-elements'][5][0] = __('Fotos', 'cf_v3');            // Photos

}

/**
 * 
 * Adiciona página customizada de gestão do usuários,
 * quando a restrição de controle de usuários estiver ativa
 * 
 * cfv3_add_menu_items
 *
 * @return void
 */
function cfv3_add_menu_items()
{
    $cfv3_disable_users_restriction = cfv3_get_option('cfv3_disable_users_restriction');

    if ($cfv3_disable_users_restriction)
        return;

    add_menu_page(__('Usuários', 'cf_v3'), __('Usuários', 'cf_v3'), 'edit_theme_options', 'new-users', 'cf_v3_new_users_page', 'dashicons-groups', null);
}


/**
 * 
 * Conteúdo da página de usuários,
 * quando o restrição de controle de usuários estiver ativa
 * 
 * cf_v3_new_users_page
 *
 * @return void
 */
function cf_v3_new_users_page()
{
    require_once 'cf-v3-admin-new-users-page.php';
}

/**
 * 
 * Remove o Contact Form 7 do admin menu
 * (Legado)
 * 
 * cfv3_remove_contact_form7_admin_menu_item
 *
 * @return void
 */
function cfv3_remove_contact_form7_admin_menu_item()
{
    remove_menu_page('wpcf7');    // CF7
}

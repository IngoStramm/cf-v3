<?php

/**
 * Controla se deve restringir a gestão de usuários para a role "editor"
 * 
 * 
 * cfv3_editor_role_caps
 *
 * @return void
 */
function cfv3_editor_role_caps()
{
    // 
    $cfv3_disable_users_restriction = cfv3_get_option('cfv3_disable_users_restriction');

    // Editor
    // get the the role object
    $editor_role_object = get_role('editor');

    // add $cap capability to this role object
    $editor_role_object->add_cap('edit_theme_options');
    $editor_role_object->add_cap('rocket_purge_cache');

    if ($cfv3_disable_users_restriction) {
        $editor_role_object->add_cap('list_users');
        $editor_role_object->add_cap('edit_users');
        $editor_role_object->add_cap('delete_users');
        $editor_role_object->add_cap('create_users');
        $editor_role_object->add_cap('add_users');
        $editor_role_object->add_cap('promote_users');
        $editor_role_object->add_cap('remove_users');
    } else {
        $editor_role_object->remove_cap('list_users');
        $editor_role_object->remove_cap('edit_users');
        $editor_role_object->remove_cap('delete_users');
        $editor_role_object->remove_cap('create_users');
        $editor_role_object->remove_cap('add_users');
        $editor_role_object->remove_cap('promote_users');
        $editor_role_object->remove_cap('remove_users');
    }
}

add_action('admin_init', 'cfv3_editor_role_caps');

/**
 * Controla se deve restringir a gestão de usuários para a role "shop_editor" (WooCommerce)
 * 
 * cfv3_shop_editor_role_caps
 *
 * @return void
 */
function cfv3_shop_editor_role_caps()
{
    $shop_editor_role_object = get_role('shop_editor');
    if (!isset($shop_editor_role_object))
    return;
    $shop_editor_role_object->add_cap('rocket_purge_cache');
    $cfv3_disable_users_restriction = cfv3_get_option('cfv3_disable_users_restriction');
    if ($cfv3_disable_users_restriction) {
        $shop_editor_role_object->add_cap('list_users');
        $shop_editor_role_object->add_cap('edit_users');
        $shop_editor_role_object->add_cap('delete_users');
        $shop_editor_role_object->add_cap('create_users');
        $shop_editor_role_object->add_cap('add_users');
        $shop_editor_role_object->add_cap('promote_users');
        $shop_editor_role_object->add_cap('remove_users');
    } else {
        $shop_editor_role_object->remove_cap('list_users');
        $shop_editor_role_object->remove_cap('edit_users');
        $shop_editor_role_object->remove_cap('delete_users');
        $shop_editor_role_object->remove_cap('create_users');
        $shop_editor_role_object->remove_cap('add_users');
        $shop_editor_role_object->remove_cap('promote_users');
        $shop_editor_role_object->remove_cap('remove_users');
    }
}

add_action('admin_init', 'cfv3_shop_editor_role_caps');


// criar função que adiciona custom capability para o usuário editor
function cfv3_wp_rocket_editor_capability()
{
    $user = wp_get_current_user();
    // cfv3_debug($user->roles);
    $editor_role = get_role('editor');
    $editor_role->add_cap('rocket_purge_cache');
    $shop_editor_role = get_role('shop_editor');
    $shop_editor_role->add_cap('rocket_purge_cache');
}
// add_action('admin_init', 'cfv3_wp_rocket_editor_capability');

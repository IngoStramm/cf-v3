<?php

// remove a função "admin" das opções de função de usuário, na criação/edição de usuário
add_filter('ure_show_additional_capabilities_section', 'cfa_desabilita_other_roles');

/**
 * Verificação se é ou não um usuário com a role "administrator"
 * 
 * cfa_desabilita_other_roles
 *
 * @return boolean
 */
function cfa_desabilita_other_roles()
{
    return (cfv3_get_user_role() !== 'administrator') ? false : true;
}

/*
*
* Previne de qualquer usuário que NÃO for um Administrador,
* de editar um usuário Administrador
* Por ex: um Editor não pode criar/editar/excluir um Administrador
*
*/
/**
 * Cfa_Previne_Edicao_Admin
 */
class Cfa_Previne_Edicao_Admin
{

    // Add our filters    
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        add_filter('editable_roles', array(&$this, 'editable_roles'));
        add_filter('map_meta_cap', array(&$this, 'map_meta_cap'), 10, 4);
    }

    /**
     * Remove 'Administrator' from the list of roles if the current user is not an admin
     * 
     * editable_roles
     *
     * @param  array $roles
     * @return array
     */
    function editable_roles($roles)
    {
        if (isset($roles['administrator']) && !current_user_can('administrator')) {
            unset($roles['administrator']);
        }
        return $roles;
    }

    /**
     * If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
     * 
     * map_meta_cap
     *
     * @param  array $caps
     * @param  string $cap
     * @param  int $user_id
     * @param  array $args
     * @return array
     */
    function map_meta_cap($caps, $cap, $user_id, $args)
    {

        switch ($cap) {
            case 'edit_user':
            case 'remove_user':
            case 'promote_user':
                if (isset($args[0]) && $args[0] == $user_id)
                    break;
                elseif (!isset($args[0]))
                    $caps[] = 'do_not_allow';
                $other = new WP_User(absint($args[0]));
                if ($other->has_cap('administrator')) {
                    if (!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            case 'delete_user':
            case 'delete_users':
                if (!isset($args[0]))
                    break;
                $other = new WP_User(absint($args[0]));
                if ($other->has_cap('administrator')) {
                    if (!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            default:
                break;
        }
        return $caps;
    }
}

add_action('init', 'cfv3_block_wp_admin_init', 0);

/**
 * Previne que usuários não administradores acessem o admin durante o modo de manutenção
 * 
 * cfv3_block_wp_admin_init
 *
 * @return void
 */
function cfv3_block_wp_admin_init()
{
    if (get_theme_mod('maintenance_mode', 0) > 0) {
        if (strpos(strtolower($_SERVER['REQUEST_URI']), '/wp-admin/') !== false) {
            if (!current_user_can('manage_options')) {
                wp_redirect(get_option('siteurl'), 302);
            }
        }
    }
}


$cfv3_user_caps = new Cfa_Previne_Edicao_Admin();

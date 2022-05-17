<?php

add_filter('get_user_option_admin_color', 'cfv3_update_user_option_admin_color', 5);

/**
 * Define o esquema de cores do WordPress para TODOS os usuários
 *
 * cfv3_update_user_option_admin_color
 *
 * @param  string $color_scheme
 * @return string
 */
function cfv3_update_user_option_admin_color($color_scheme)
{
    $color_scheme = 'ectoplasm';

    return $color_scheme;
}

// Remove a opção de mudar o esquema de cores do WordPress para TODOS os usuários
remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');

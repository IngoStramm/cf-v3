<?php

/**
 * Função de debug
 *
 * cfv3_debug
 *
 * @param  mixed $debug
 * @return void
 */
function cfv3_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}


/**
 * Retorna a função do usuário
 * 
 * cfv3_get_user_role
 *
 * @return string/boolean $role/false
 */
function cfv3_get_user_role()
{
    if (is_user_logged_in()) :
        $user = wp_get_current_user();
        $roles = (array) $user->roles;
        return $roles[0];
    else :
        return false;
    endif;
}

/**
 * cfv3_is_not_administrador
 *
 * @return boolean
 */
function cfv3_is_not_administrador()
{
    return cfv3_get_user_role() !== 'administrator';
}

/**
 * cfv3_is_not_editor
 *
 * @return boolean
 */
function cfv3_is_not_editor()
{
    return cfv3_get_user_role() !== 'editor';
}

/**
 * Retorna a opção criada pelo CMB2
 * 
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function cfv3_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('cfv3_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('cfv3_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}

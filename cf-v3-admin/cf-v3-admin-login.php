<?php

add_filter('login_headerurl', 'cfv3_admin_login_logo_url');

/**
 * 
 * Altera a URL do logo, na tela de login
 * 
 * cfv3_admin_login_logo_url
 *
 * @return string
 */
function cfv3_admin_login_logo_url()
{
    return home_url();
}

add_filter('login_headertitle', 'cfv3_admin_login_logo_title');

/**
 * 
 * Altera o texto do link do logo, na tela de login (por padrão, não é exibido)
 * 
 * cfv3_admin_login_logo_title
 *
 * @return string
 */
function cfv3_admin_login_logo_title()
{
    $site_title = get_bloginfo('name');
    return $site_title;
}

// Remove o switcher de idioma da tela de login
add_filter('login_display_language_dropdown', '__return_false');

add_filter('login_body_class', 'cfv3_admin_login_body_class');

function cfv3_admin_login_body_class($classes)
{
    return array_merge($classes, array('cf-v3-login'));
}

add_action('login_enqueue_scripts', 'cf_v3_admin_login_style');

/**
 * 
 * Adiciona a customização de estilo da tela de login
 * 
 * cf_v3_admin_login_style
 *
 * @return string
 */
function cf_v3_admin_login_style()
{ ?>
    <style>
        :root {
            --branco: #fff;
            --roxo: #291746;
            --quase-preto: #383c44;
        }

        body.cf-v3-login {
            display: flex;
            justify-items: center;
            justify-content: center;
            align-items: start;
            background-color: var(--roxo);
            background-position: right center;
            background-repeat: no-repeat;
            background-size: 60%;
            background-image: url(<?php echo CFV3_URL; ?>/assets/images/login-background.jpg);
        }

        body.cf-v3-login div#login {
            display: grid;
            align-content: start;
            justify-content: center;
            min-height: 550px;
            margin: 0;
            padding: 0;
            max-width: 100%;
            padding-inline: 15px;
            background-color: var(--branco);
        }

        body.cf-v3-login div#login>* {
            max-width: 350px;
        }

        body.cf-v3-login #login h1 a,
        body.cf-v3-login .login h1 a {
            height: 85px;
            width: 100%;
            margin-block: 0;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url(<?php echo CFV3_URL; ?>/assets/images/logo.png);
        }

        body.cf-v3-login.login form {
            border-radius: 30px;
            box-shadow: 0px 1px 0px 0px rgb(0 0 0 / 13%);
            border: none;
            overflow: visible;
        }

        body.cf-v3-login.login.wp-core-ui form .button-primary {
            border-radius: 30px;
            background-color: var(--quase-preto);
            border-color: var(--quase-preto);
        }

        body.cf-v3-login.login.wp-core-ui form .button-primary:active,
        body.cf-v3-login.login.wp-core-ui form .button-primary:focus,
        body.cf-v3-login.login.wp-core-ui form .button-primary:hover,
        body.cf-v3-login.login.wp-core-ui form .button-primary:focus:hover {
            background-color: var(--roxo);
            border-color: var(--roxo);
        }

        @media (min-width: 426px) {
            body.cf-v3-login {
                align-items: stretch;
            }

            body.cf-v3-login div#login {
                width: 50%;
                align-content: center;
                margin-left: 0;
                margin-right: auto;
            }
        }
    </style>
<?php }

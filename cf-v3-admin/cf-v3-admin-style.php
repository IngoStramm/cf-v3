<?php

add_action('init', 'cfv3_is_not_an_administrator_admin_style');

function cfv3_is_not_an_administrator_admin_style()
{
    if (!cfv3_is_not_administrador())
        return;

    add_action('admin_head', 'cfv3_admin_style');
}

/**
 * 
 * 
 * Esconde via CSS elementos do admin
 * 
 * cfv3_admin_style
 *
 * @return void
 */
function cfv3_admin_style()
{
    $screen = get_current_screen(); ?>
    <style>
        /* menu de ajuda da barra superior adm */
        #screen-meta,
        #screen-meta-links,

        /* erro no topo etc, wp */
        .error,
        .notice,
        .notice-warning,
        .welcome-panel,
        #wpfooter,
        .woocommerce-message,
        .update-nag,

        /* elementor */
        .xraXtbWi-Eo_RJdZ-u28M,
        .-cJ75CRkRvVzmNwM_Vydw,
        ._1nYFx_Z-zJnn6NVOg4jxww,
        .OAx3WmyjUg4Xy7qdA1_WW,

        /* Elements */
        #toplevel_page_envato-elements .wp-submenu.wp-submenu-wrap .wp-first-item {
            display: none !important;
        }
    </style>
    <?php

    if ($screen->id == 'nav-menus') : ?>
        <style>
            a.page-title-action.hide-if-no-customize,
            h2.nav-tab-wrapper.wp-clearfix,
            .manage-menus .add-new-menu-action,
            span.add-new-menu-action,
            .menu-settings,
            li#add-category,
            li#woocommerce_endpoints_nav_link,
            span.delete-action,
            /* .major-publishing-actions label.menu-name-label, */
            /* .major-publishing-actions input#menu-name, */
            /* span.add-edit-menu-action, */
            .nav-tab-wrapper {
                display: none !important;
            }

            li#add-post-type-page,
            li#add-post-type-post,
            li#add-custom-links {
                display: block !important;
            }

            .manage-menus span.add-edit-menu-action {
                font-size: 0;
            }

            .manage-menus span.add-edit-menu-action::before {
                content: 'Edite o menu.';
                font-size: 1rem;
            }

            .major-publishing-actions label.menu-name-label {
                pointer-events: none;
            }

            .major-publishing-actions input#menu-name {
                width: auto;
                border: none;
                background: transparent;
                /* cursor: default; */
                pointer-events: none;
            }

            .major-publishing-actions input#menu-name:focus {
                box-shadow: 0 0 0 transparent;
                pointer-events: none;
            }
        </style>
    <?php elseif ($screen->id == 'users') : ?>
        <style>
            #new_role,
            #changeit,
            #ure_grant_roles {
                display: none;
            }
        </style>
    <?php elseif ($screen->id == 'contato_page_wpcf7-new') : ?>
        <style>
            #informationdiv {
                display: none;
            }
        </style>
    <?php elseif ($screen->id == 'toplevel_page_wpcf7') : ?>
        <style>
            #informationdiv {
                display: none;
            }
        </style>
<?php
    endif;
}

<?php

add_action('init', 'cfv3_is_not_an_administrator_admin_notices');

function cfv3_is_not_an_administrator_admin_notices()
{

    if (!cfv3_is_not_administrador())
        return;

    // Pega os avisos do Painel CF
    add_action('admin_notices', 'cfp_admin_notice');

    /**
     * Pega e exibe as notificações do painel Converte Fácil
     * @link: https://painel.convertefacil.com.br
     * 
     * cfp_admin_notice
     *
     * @return void
     */
    function cfp_admin_notice()
    {

        // $start_time = microtime(true);

        if (false === ($notices = get_transient('cf_panel_notices'))) {

            $xml = simplexml_load_file("https://painel.convertefacil.com.br/feed//?post_type=notice");

            if (!$xml) return;

            $notices = [];

            foreach ($xml->children() as $node_1) {
                foreach ($node_1->item as $node_2) {
                    cfv3_debug($node_2);
                    $arr_notice = [];

                    $arr_notice['title'] = (string)$node_2->title === 'Sem título' ? '' : (string)$node_2->title;
                    $arr_notice['content'] = trim((string)$node_2->content);
                    $arr_notice['notice_type'] = (string)$node_2->notice_type;

                    $arr_screens = [];
                    foreach ($node_2->screens as $item) {
                        $arr_screens[] = (string)$item->screen;
                    }
                    $arr_notice['screens'] = $arr_screens;

                    $notices[(int)$node_2->menu_order] = $arr_notice;
                }
            }

            ksort($notices);
            set_transient('cf_panel_notices', $notices, HOUR_IN_SECONDS);
        }

        // $end_time = microtime(true);
        // $run_time = ($end_time - $start_time);
        // cfv3_debug($run_time);

        // Array com as telas do wp-admin
        $all_screens = array(
            'dashboard' => array(
                'dashboard',
            ),
            'posts' => array(
                'edit-post', 'post', 'edit-category', 'edit-post_tag',
            ),
            'media' => array(
                'upload', 'media',
            ),
            'pages' => array(
                'edit-page', 'page',
            ),
            'comments' => array(
                'edit-comments',
            ),
            'cf7' => array(
                'toplevel_page_wpcf7', 'toplevel_page_wpcf7-new',
            ),
            'templates' => array(
                'toplevel_page_envato-elements',
            ),
            'menus' => array(
                'nav-menus',
            ),
            'profile' => array(
                'profile',
            ),
            'users' => array(
                'toplevel_page_new-users',
            ),
        );

        $current_screen = get_current_screen();
        $current_screen = $current_screen->id;

        foreach ($notices as $k => $notice) :
            $notice_screens = [];
            $content = '<div class="notice ' . $notice['notice_type'] . ' is-dismissible" style="display: block !important;">';
            $content .= '<h3>' . $notice['title'] . '</h3>';
            $content .= $notice['content'];
            $content .= '</div>';

            foreach ($all_screens as $k => $screens) :
                if (in_array($k, $notice['screens'])) :
                    foreach ($screens as $screen) :
                        // cfv3_debug( $screen );
                        if ($current_screen == $screen) :
                            echo $content;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endforeach;
    }
}

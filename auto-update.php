<?php

add_filter('plugins_api', 'cf_plugin_info', 20, 3);
/*
 * $res empty at this step
 * $action 'plugin_information'
 * $args stdClass Object ( [slug] => woocommerce [is_ssl] => [fields] => Array ( [banners] => 1 [reviews] => 1 [downloaded] => [active_installs] => 1 ) [per_page] => 24 [locale] => en_US )
 */
function cf_plugin_info($res, $action, $args)
{

    // do nothing if this is not about getting plugin information
    if ('plugin_information' !== $action) {
        return false;
    }

    $plugin_slug = 'cf-v3'; // we are going to use it in many places in this function

    // do nothing if it is not our plugin
    if ($plugin_slug !== $args->slug) {
        return false;
    }

    // trying to get from cache first
    if (false == $remote = get_transient('cf_update_' . $plugin_slug)) {

        // info.json is the file with the actual plugin information on your server
        $remote = wp_remote_get('https://raw.githubusercontent.com/IngoStramm/cf-v3/master/info.json', array(
            'timeout' => 10,
            'headers' => array(
                'Accept' => 'application/json'
            )
        ));

        if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
            set_transient('cf_update_' . $plugin_slug, $remote, 43200); // 12 hours cache
        }
    }

    if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {

        $remote = json_decode($remote['body']);
        $res = new stdClass();

        $res->name = $remote->name;
        $res->slug = $plugin_slug;
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = '<a href="https://agencialaf.com">Ingo Stramm</a>';
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->requires_php = '7.1';
        $res->last_updated = $remote->last_updated;
        $res->sections = array(
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog
            // you can add your custom sections (tabs) here
        );

        // in case you want the screenshots tab, use the following HTML format for its content:
        // <ol><li><a href="IMG_URL" target="_blank"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
        if (!empty($remote->sections->screenshots)) {
            $res->sections['screenshots'] = $remote->sections->screenshots;
        }

        // $res->banners = array(
        //     'low' => 'https://YOUR_WEBSITE/banner-772x250.jpg',
        //     'high' => 'https://YOUR_WEBSITE/banner-1544x500.jpg'
        // );
        return $res;
    }

    return false;
}


add_filter('site_transient_update_plugins', 'cf_push_update');

function cf_push_update($transient)
{

    if (empty($transient->checked)) {
        return $transient;
    }

    // trying to get from cache first, to disable cache comment 10,20,21,22,24
    if (false == $remote = get_transient('cf_upgrade_cf_v3')) {

        // info.json is the file with the actual plugin information on your server
        $remote = wp_remote_get('https://raw.githubusercontent.com/IngoStramm/cf-v3/master/info.json', array(
            'timeout' => 10,
            'headers' => array(
                'Accept' => 'application/json'
            )
        ));

        if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
            set_transient('cf_upgrade_cf_v3', $remote, 43200); // 12 hours cache
        }
    }

    if ($remote) {

        $remote = json_decode($remote['body']);

        // your installed plugin version should be on the line below! You can obtain it dynamically of course 
        if ($remote && version_compare('1.0', $remote->version, '<') && version_compare($remote->requires, get_bloginfo('version'), '<')) {
            $res = new stdClass();
            $res->slug = 'cf-v3';
            $res->plugin = 'cf-v3/cf-v3.php'; // it could be just cf-v3.php if your plugin doesn't have its own directory
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[$res->plugin] = $res;
            //$transient->checked[$res->plugin] = $remote->version;
        }
    }
    return $transient;
}

add_action('upgrader_process_complete', 'cf_after_update', 10, 2);

function cf_after_update($upgrader_object, $options)
{
    if ($options['action'] == 'update' && $options['type'] === 'plugin') {
        // just clean the cache when new plugin version is installed
        delete_transient('cf_upgrade_cv_v3');
    }
}

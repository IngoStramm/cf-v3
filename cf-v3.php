<?php
 
/**
 * Plugin Name: ConverteFacil 3 Admin
 * Plugin URI: https://agencialaf.com
 * Description: Este plugin é parte integrante do ConverteFácil.
 * Version: 2.0.0
 * Author: Ingo Stramm
 * Text Domain: cfv3
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('CFV3_DIR', plugin_dir_path(__FILE__));
define('CFV3_URL', plugin_dir_url(__FILE__));

function cfv3_debug($debug)
{
	echo '<pre>';
	var_dump($debug);
	echo '</pre>';
}

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

add_action('init', 'cfv3_checka_administrador');

function cfv3_checka_administrador()
{
	if (cfv3_get_user_role() !== 'administrator')
		cfv3_customizacaoAdmin();
}

/*
*
* As funções que estiverem dentro desta função ("cfv3_customizacaoAdmin()")
* só serão executadas se o usuário não for um administrador
*
*/
function cfv3_customizacaoAdmin()
{

	// get the the role object
	$role_object = get_role('editor');

	// add $cap capability to this role object
	$role_object->add_cap('edit_theme_options');
	$role_object->remove_cap('list_users');
	$role_object->remove_cap('edit_users');
	$role_object->remove_cap('delete_users');
	$role_object->remove_cap('create_users');
	$role_object->remove_cap('add_users');
	$role_object->remove_cap('promote_users');
	$role_object->remove_cap('remove_users');

	/*
	 *
	 * Dashboard: Customiza os Widgets
	 *
	 */
	add_action('wp_dashboard_setup', 'cfa_dashboard_widgets');

	function cfa_dashboard_widgets()
	{
		global $wp_meta_boxes;


		// Remove os Widgets
		// Agora
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		// Elementor
		unset($wp_meta_boxes['dashboard']['normal']['core']['e-dashboard-overview']);
		// Samrt Crawl
		unset($wp_meta_boxes['dashboard']['normal']['core']['wds_sitemaps_dashboard_widget']);
		// Postagem rápida
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		// Novidades WP
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		// Atividades
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		// Health Check
		unset($wp_meta_boxes['dashboard']['normal']['core']['health_check_status']);
		// Booking
		unset($wp_meta_boxes['dashboard']['normal']['core']['booking_dashboard_widget']);
		// Woocommerce Avaliações Recentes
		remove_meta_box('woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');
		// Woocommerce Status
		remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
		// Yoast
		remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
		remove_meta_box('yith_dashboard_products_news', 'dashboard', 'normal');
		remove_meta_box('yith_dashboard_blog_news', 'dashboard', 'normal');
	}

	/*
	 *
	 * Remove Menus do sidebar
	 *
	 */
	add_action('admin_menu', 'cfv3_remove_menus_editor');

	function cfv3_remove_menus_editor()
	{
		global $menu, $submenu;
		add_menu_page(__('Editar Menus'), __(' Menus '), 'edit_theme_options', 'nav-menus.php', null, 'dashicons-menu', 60);
		remove_menu_page('edit.php?post_type=elementor_library');                  //Elementor
		remove_menu_page('themes.php');                 //Appearance
		remove_menu_page('tools.php');                  //Tools
		remove_menu_page('wds_wizard');                  //Tools
		remove_menu_page('edit.php?post_type=blocks');                  //blocks
		remove_menu_page('qligg');                  //blocks

		// Envato Elements
		remove_submenu_page('envato-elements', 'envato-elements#/welcome');
		remove_submenu_page('envato-elements', 'envato-elements#/settings');
		remove_submenu_page('envato-elements', 'envato-elements#/template-kits/free-kits');
		remove_submenu_page('envato-elements', 'envato-elements#/template-kits/free-blocks');
		remove_submenu_page('envato-elements', 'envato-elements#/template-kits/installed-kits');
	}

	/*
	 *
	 * Renomeia Menus do sidebar
	 *
	 */
	add_action('admin_menu', 'cfv3_rename_menus');

	function cfv3_rename_menus()
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
		// unset( $submenu[ 'envato-elements' ][ 1 ] ); // Remove o segundo Template Kits
		endif;

		if (isset($submenu['envato-elements'][5]))
			$submenu['envato-elements'][5][0] = __('Fotos', 'cf_v3');			// Photos

	}

	add_action('admin_menu', 'cfv3_add_user_menu');

	function cfv3_add_user_menu()
	{
		add_menu_page(__('Usuários', 'cf_v3'), __('Usuários', 'cf_v3'), 'edit_theme_options', 'new-users', 'cf_v3_new_users_page', 'dashicons-groups', null);
	}

	function cf_v3_new_users_page()
	{
?>
		<div class="wrap" id="new-users-page">

			<p><img src="<?php echo CFV3_URL; ?>/assets/images/novos-usuarios.jpg" alt="<?php _e('Novos usuários', 'cf_v3'); ?>" style="max-width: 100%; height: auto; display: block; margin: 0;"></p>

			<p>Caso deseje adicionar novas contas de usuários com acesso ao painel administrativo do Converte Fácil, entre em contato com o nosso suporte, solicitando a criação das contas.</p>

			<p>Lembre-se que precisaremos das seguintes informações:</p>

			<ul>
				<li>> Nome do novo usuário</li>
				<li>> E-mail do usuário</li>
				<li>> Nível de acesso* do usuário</li>
			</ul>

			<p><strong>*Nível de Acesso:</strong> o site do Converte Fácil permite que os usuários possuam diferentes níveis de acesso. Isso possibilita que você tenha outros usuários com o mesmo acesso que o seu, usuários que possam apenas publicar páginas e posts, e usuários que possam apenas criar páginas e posts que precisarão ser aprovados para serem publicados.</p>

			<p>Estes são os Níveis de Acesso que você pode escolher:</p>

			<ul>
				<li>> <code>Editor</code> (tem acesso total ao Converte Fácil)</li>
				<li>> <code>Autor</code> (pode publicar, editar, apagar apenas as páginas e posts criados por ele mesmo)</li>
				<li>> <code>Contribuidor</code> (pode criar páginas e posts, mas eles precisarão ser aprovados para serem publicados)</li>
			</ul>

			<h3>Canais de comunicação do Suporte</h3>
			<ul>
				<li><a target="_blank" href="https://convertefacil.com/suporte">https://convertefacil.com/suporte</a></li>
			</ul>

			<?php
			$suporte_user = get_user_by('email', 'suporte@convertefacil.com.br');
			$args = array(
				'role__not_in'		=> array('administrator', 'subscriber'),
				'exclude'			=> $suporte_user->id
			);
			$users = get_users($args);
			?>

			<?php if ($users) : ?>

				<?php global $wp_roles; ?>

				<h3><?php _e('Usuários Cadastrados', 'cfv3'); ?></h3>

				<table class="wp-list-table widefat fixed striped posts">

					<thead>

						<tr>

							<th cope="col" class="">

								<span><?php _e('Nome', 'cfv3'); ?></span>

							</th>
							<!-- /.manage-column column-title column-primary sortable desc -->

							<th cope="col" class="">

								<span><?php _e('E-mail', 'cfv3'); ?></span>

							</th>
							<!-- /.manage-column column-title column-primary sortable desc -->

							<th cope="col" class="">

								<span><?php _e('Função', 'cfv3'); ?></span>

							</th>
							<!-- /.manage-column column-title column-primary sortable desc -->

						</tr>

					</thead>

					<tbody>

						<?php foreach ($users as $user) : ?>

							<?php
							$user_id = $user->ID;
							$user_info = get_userdata($user_id);
							$user_name = $user_info->display_name;
							$user_email = $user_info->user_email;
							$user_role = implode(', ', $user_info->roles);
							$user_role = translate_user_role($wp_roles->roles[$user_role]['name']);
							?>

							<tr>

								<td><?php echo $user_name; ?></td>
								<td><?php echo $user_email; ?></td>
								<td><?php echo $user_role; ?></td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>
				<!-- /.wp-list-table widefat fixed striped posts -->

			<?php endif; ?>

		</div>
		<!-- /#new-users-page.wrap -->
	<?php
	}



	add_action('admin_bar_menu', 'cfv3_remove_bar_menu_items', 999);

	function cfv3_remove_bar_menu_items($wp_admin_bar)
	{
		$wp_admin_bar->remove_node('wds_wizard');
	}

	add_action('wp_head', 'cfv3_hide_avatar_style');
	add_action('admin_head', 'cfv3_hide_avatar_style');

	function cfv3_hide_avatar_style()
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

	add_action('admin_head', 'cfv3_menus_edit_style');

	function cfv3_menus_edit_style()
	{
		$screen = get_current_screen();

	?>
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
				.major-publishing-actions label.menu-name-label,
				.major-publishing-actions input#menu-name,
				.nav-tab-wrapper {
					display: none !important;
				}

				li#add-post-type-page,
				li#add-post-type-post,
				li#add-custom-links {
					display: block !important;
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
<?php endif;
	}

	add_action('wp_dashboard_setup', 'cfv3_get_widgets');

	function cfv3_get_widgets()
	{
		$request = wp_remote_get('https://painel.convertefacil.com.br/wp-json/wp/v2/widget?_embed');

		$body = wp_remote_retrieve_body($request);

		$data = json_decode($body, true);

		if (!$data) :
			return;
		endif;

		$widgets = [];

		foreach ($data as $widget) :

			$widgets[$widget['menu_order']] = array(
				'title' => $widget['title']['rendered'],
				'content' => trim($widget['content']['rendered']),
				'thumb' => $widget['_embedded']['wp:featuredmedia'][0]['source_url'],
				'position' => $widget['post-meta-fields']['widget_options_select'][0]
			);

		endforeach;

		ksort($widgets);

		// cfv3_debug( $widgets );

		foreach ($widgets as $k => $widget) :
			$content = '<img src="' . $widget['thumb'] .  '" alt="' . $widget['title'] . '" style="width: 100%; height: auto; display: block; margin: auto;" />';
			$content .= $widget['content'];
			add_meta_box('cf-widget-' . $k, $widget['title'], function () use ($content) {
				echo $content;
			}, 'dashboard', $widget['position'], 'high');
		endforeach;
	}

	add_action('admin_notices', 'cfp_admin_notice');

	function cfp_admin_notice()
	{

		$request = wp_remote_get('https://painel.convertefacil.com.br/wp-json/wp/v2/notice');

		$body = wp_remote_retrieve_body($request);

		$data = json_decode($body, true);

		if (!$data)
			return;

		$current_screen = get_current_screen();
		$current_screen = $current_screen->id;

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

		$notices = [];


		foreach ($data as $notice) :

			$notices[$notice['menu_order']] = array(
				'title' => $notice['title']['rendered'],
				'content' => trim($notice['content']['rendered']),
				'notice_type' => $notice['post-meta-fields']['notice_options_notice_type'][0],
				'screens' => $notice['post-meta-fields']['notice_options_screen'],
			);

		endforeach;

		ksort($notices);

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
} // Fim cfv3_customizacaoAdmin()

function cfv3_add_script()
{
	wp_register_script('cf-v3-script', CFV3_URL . 'assets/js/cf-v3.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('cf-v3-script');
}

add_action('init', 'cfv3_checka_user');

function cfv3_checka_user()
{
	if (cfv3_get_user_role() !== 'administrator' && cfv3_get_user_role() !== 'editor')
		cfv3_customizacaoUsers();
}

/*
*
* As funções que estiverem dentro desta função ("cfv3_customizacaoUsers()")
* só serão executadas se o usuário não for um administrador e nem um editor
*
*/
function cfv3_customizacaoUsers()
{

	/*
	 *
	 * Remove Menus do sidebar
	 *
	 */
	add_action('admin_menu', 'cfv3_remove_menus_users');

	function cfv3_remove_menus_users()
	{
		global $menu, $submenu;
		remove_menu_page('wpcf7');	// CF7

	}
} //  Fim cfv3_customizacaoUsers()



add_filter('ure_show_additional_capabilities_section', 'cfa_desabilita_other_roles');

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
class Cfa_Previne_Edicao_Admin
{

	// Add our filters
	function __construct()
	{
		add_filter('editable_roles', array(&$this, 'editable_roles'));
		add_filter('map_meta_cap', array(&$this, 'map_meta_cap'), 10, 4);
	}

	// Remove 'Administrator' from the list of roles if the current user is not an admin
	function editable_roles($roles)
	{
		if (isset($roles['administrator']) && !current_user_can('administrator')) {
			unset($roles['administrator']);
		}
		return $roles;
	}

	// If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
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

// Previne que usuários não administradores acessem o admin durante o modo de manutenção
add_action('init', 'cfv3_block_wp_admin_init', 0);

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

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/IngoStramm/cf-v3/master/info.json',
	__FILE__,
	'cf-v3'
);
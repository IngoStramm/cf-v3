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
        'role__not_in'        => array('administrator', 'subscriber'),
        'exclude'            => $suporte_user->id
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
document.addEventListener('DOMContentLoaded', function () {


    function myAdminNotice(msg, reload_page) {

        /* create notice div */

        var div = document.createElement('div');
        div.classList.add('notice', 'notice-info', 'is-dismissible');

        /* create paragraph element to hold message */

        var p = document.createElement('p');

        /* Add message text */

        p.appendChild(document.createTextNode(msg));

        // Optionally add a link here

        /* Add the whole message to notice div */


        div.appendChild(p);


        /* Create Dismiss icon */

        var b = document.createElement('button');
        b.setAttribute('type', 'button');
        b.classList.add('notice-dismiss');

        /* Add screen reader text to Dismiss icon */

        var bSpan = document.createElement('span');
        bSpan.classList.add('screen-reader-text');
        bSpan.appendChild(document.createTextNode('Dismiss this notice'));
        b.appendChild(bSpan);

        /* Add Dismiss icon to notice */

        div.appendChild(b);
        div.setAttribute('style', 'display: block !important;');

        /* Insert notice after the first h1 */

        var h1 = document.getElementsByTagName('h1')[0];
        h1.parentNode.insertBefore(div, h1.nextSibling);


        /* Make the notice dismissable when the Dismiss icon is clicked */

        b.addEventListener('click', function () {
            if (reload_page) {
                p.innerText = 'Recarregando a página...';
                location.reload();
            } else {
                div.parentNode.removeChild(div);
            }
        });
    }

    const parent_btn = document.getElementById('wp-admin-bar-cfv3-clear-cache');

    if (!parent_btn) {
        return;
    }

    const btn = parent_btn.getElementsByTagName('a')[0];

    btn.addEventListener('click', function (e) {
        e.preventDefault();
        var is_disabled = btn.getAttribute('disabled');

        if (is_disabled === 'true') {
            return;
        }

        btn.setAttribute('disabled', true);
        const action = 'wphb_global_clear_cache';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', ajax_object.ajax_url + '?action=' + action);
        xhr.onload = function (data) {
            if (xhr.status === 200) {
                myAdminNotice('Cache limpo com sucesso!', true);
            }
            btn.setAttribute('disabled', false);
        };

        xhr.send();
    });
});
<?php
function ks_direct_external_link($link_html, $href, $label) {
    return '<a href="' . attribute_escape($href) . '">' . attribute_escape($label) . '</a>';
}
add_filter('external_link/ktai_style.php', 'ks_direct_external_link', 10, 3);
?>


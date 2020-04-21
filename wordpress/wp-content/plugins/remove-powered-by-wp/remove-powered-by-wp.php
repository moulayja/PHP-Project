<?php
/*
 * Plugin Name: Remove "Powered by Wordpress"
 * Version: 1.3.1
 * Plugin URI: https://webd.uk/remove-powered-by-wp/
 * Description: Removes the Wordpress credit on the default Wordpress theme and inserts a widget area
 * Author: webd.uk
 * Author URI: https://webd.uk
 * Text Domain: remove-powered-by-wp
 */



if (!class_exists('remove_powered_by_wp_class')) {

	class remove_powered_by_wp_class {

        public static $compatible_themes = array(
            'Twenty Ten' => 'twentyten',
            'Twenty Eleven' => 'twentyeleven', 
            'Twenty Twelve' => 'twentytwelve', 
            'Twenty Thirteen' => 'twentythirteen', 
            'Twenty Fourteen' => 'twentyfourteen', 
            'Twenty Fifteen' => 'twentyfifteen', 
            'Twenty Sixteen' => 'twentysixteen',
            'Twenty Seventeen' => 'twentyseventeen',
            'Twenty Nineteen' => 'twentynineteen',
            'Twenty Twenty' => 'twentytwenty'
        );

        public static $alternative_plugins = array(
            'Twenty Seventeen' => 'twentyseventeen',
            'Twenty Nineteen' => 'twentynineteen',
            'Twenty Twenty' => 'twentytwenty'
        );

		function __construct() {

            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'rpbw_add_plugin_settings_link'));
            add_action('customize_register', array($this, 'rpbw_customize_register'));
            add_action('wp_head' , array($this, 'rpbw_header_output'));
            if (get_template() != 'twentynineteen' && get_template() != 'twentytwenty') { add_action('widgets_init', array($this, 'rpbw_site_info_sidebar_init')); }
            add_action('admin_notices', array($this, 'rpbw_admin_notice'));
            add_action('wp_ajax_dismiss_rpbw_notice_handler', array($this, 'rpbw_ajax_notice_handler'));

		}

		function rpbw_add_plugin_settings_link($links) {
			$settings_link = '<a href="' . add_query_arg('autofocus[section]', (get_template() == 'twentytwenty' ? 'options' : 'theme_options'), admin_url('customize.php')) . '" title="' . __('Settings', 'remove-powered-by-wp') . '">' . __('Settings', 'remove-powered-by-wp') . '</a>';
			array_unshift( $links, $settings_link );
			return $links;

		}

        function rpbw_customize_register($wp_customize) {

            if (get_template() != 'twentyseventeen' & get_template() != 'twentytwenty') {

                $wp_customize->add_section('theme_options', array(
                    'title'    => __('Theme Options', 'remove-powered-by-wp'),
                    'priority' => 130
                ));

            }

            $wp_customize->add_setting('remove_powered_by_wordpress', array(
                'default'       => false,
                'type'          => 'theme_mod',
                'transport'     => 'refresh',
                'sanitize_callback' => array($this, 'rpbw_sanitize_boolean')
            ));

            if (get_template() == 'twentynineteen') {

                $description = __('Removes the "Proudly powered by WordPress" text displayed in the website footer.', 'remove-powered-by-wp');

            } elseif (get_template() == 'twentytwenty') {

                $description = __('Removes the "Powered by WordPress" text displayed in the website footer.', 'remove-powered-by-wp');

            } else {

                $description = __('Removes the "Proudly powered by WordPress" text displayed in the website footer and replaces with the content of the "Site Info" widget area.', 'remove-powered-by-wp');

            }

            $wp_customize->add_control('remove_powered_by_wordpress', array(
                'label'         => __('Remove Powered by WordPress', 'remove-powered-by-wp'),
                'description'   => $description,
                'section'       => (get_template() == 'twentytwenty' ? 'options' : 'theme_options'),
                'settings'      => 'remove_powered_by_wordpress',
                'type'          => 'checkbox'
            ));

        }

        function rpbw_sanitize_boolean($input) {

            if (isset($input)) {

                if ($input == true) {

                    return true;

                } else {

                    return false;

                }

            } else {

                return false;

            }

        }

        function rpbw_header_output() {

?>
<!--Customizer CSS--> 
<style type="text/css">
<?php

            if (get_theme_mod('remove_powered_by_wordpress')) {

                switch (get_template()) {

                    case 'twentyten':
                        add_action('twentyten_credits', array($this, 'rpbw_get_site_info_sidebar'));
                        $this->rpbw_generate_css('#footer #site-generator>a', 'display', 'remove_powered_by_wordpress', '', '', 'none');
?>
#site-generator a {
    background-image: none;
    display: inline;
    padding-left: 0;
}
#site-generator p {
    margin: 0;
}
<?php
                        break;

                    case 'twentyeleven':
                        add_action('twentyeleven_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
#site-generator>span {
    display: none;
}
#site-generator>a:last-child {
    display: none;
}
#site-generator p {
    margin: 0;
}
<?php
                        break;

                    case 'twentytwelve':
                        add_action('twentytwelve_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
<?php
                        break;

                    case 'twentythirteen':
                        add_action('twentythirteen_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
.site-info p {
    margin: 0;
}
<?php
                        break;

                    case 'twentyfourteen':
                        add_action('twentyfourteen_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
.site-info p {
    margin: 0;
}
<?php
                        break;

                    case 'twentyfifteen':
                        add_action('twentyfifteen_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-info>span {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
<?php
                        break;

                    case 'twentysixteen':
                        add_action('twentysixteen_credits', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-footer span[role=separator] {
    display: none;
}
.site-info>a:last-child {
    display: none;
}
.site-footer .site-title:after {
    display: none;
}
<?php
                        break;

                    case 'twentyseventeen':
                        add_action('get_template_part_template-parts/footer/site', array($this, 'rpbw_get_site_info_sidebar'));
?>
.site-info:last-child a:last-child {
    display: none;
}
.site-info:last-child span {
    display: none;
}
.site-info p {
    margin: 0;
}
<?php
                        break;

                    case 'twentynineteen':
                        add_action('wp_footer', array($this, 'rpbw_remove_site_info_comma'));
?>
.site-info>.imprint {
    display: none;
}
.site-name {
    margin-right: 1rem;
}
<?php
                        break;

                    case 'twentytwenty':

?>
.powered-by-wordpress {
    display: none;
}
<?php
                        break;
                }

            }

?>
</style> 
<!--/Customizer CSS-->
<?php

        }

        function rpbw_site_info_sidebar_init() {

        	register_sidebar( array(
        		'name'          => __('Site Info', 'remove-powered-by-wp'),
        		'id'            => 'site-info',
        		'description'   => __('Add widgets here to appear in your footer site info.', 'remove-powered-by-wp'),
		        'before_widget' => '',
        		'after_widget'  => '',
        		'before_title'  => '<h2 class="widget-title">',
        		'after_title'   => '</h2>',
        	) );

        }

        function rpbw_get_site_info_sidebar() {

            if (is_active_sidebar('site-info')) {

                switch (get_template()) {

                    case 'twentyten':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentyeleven':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentytwelve':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentythirteen':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentyfourteen':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentyfifteen':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentysixteen':
                        dynamic_sidebar('site-info');
                        break;

                    case 'twentyseventeen':
                        echo('<div class="site-info">');
                        dynamic_sidebar('site-info');
                        echo('</div>');
                        break;

                }

            }

        }

        function rpbw_remove_site_info_comma() {

?>
<script type="text/javascript">
    (function() {
        document.getElementsByClassName('site-info')[0].innerHTML = document.getElementsByClassName('site-info')[0].innerHTML.split('</a>,\n\t\t\t\t\t\t').join('</a>');
    })();
</script>
<?php

        }

        function rpbw_generate_css($selector, $style, $mod_name, $prefix='', $postfix='', $value='') {

            $generated_css = '';
            $mod = get_theme_mod($mod_name);

            if (!empty($mod) && $value == '') {

                $generated_css = sprintf('%s { %s: %s; }', $selector, $style, $prefix.$mod.$postfix);
                echo $generated_css;

            } elseif (!empty($mod)) {

                $generated_css = sprintf('%s { %s:%s; }', $selector, $style, $prefix.$value.$postfix);
                echo $generated_css;

            }

        }

        function rpbw_admin_notice() {

            $plugin_data = get_plugin_data(__FILE__);

            if (get_user_meta(get_current_user_id(), 'rpbw-notice-dismissed', true) != $plugin_data['Version'] && !get_option('rpbw_purchased')) {

?>

<div class="notice notice-info is-dismissible rpbw-notice">

<p><strong><?php _e('Thank you for using Remove "Powered by Wordpress" plugin', 'remove-powered-by-wp'); ?></strong></p>

<?php

                foreach (remove_powered_by_wp_class::$alternative_plugins as $key => $value) {

                    if (get_template() == $value) {

                        echo '<p>';
                        printf(
                            __('You are using %s theme so you should try %s plugin which has loads more options and features!', 'remove-powered-by-wp'),
                            '<strong>' . $key . '</strong>',
                            '<strong><a href="' . add_query_arg(array(
                                's' => 'options-for-' . sanitize_title($key),
                                'tab' => 'search',
                                'type' => 'term'
                            ), admin_url('plugin-install.php')) . '" title="' . __('Options for ' . $key, 'remove-powered-by-wp') . '">' . __('Options for ' . $key, 'remove-powered-by-wp') . '</a></strong>'
                        );
                        echo '</p>';

                    }

                }

                echo '<p>';
                _e('Can you spare some change for a poor web developer? You can help him survive the winter, feed his children and keep him off the streets. His eyes are dim. he cannot see. He\'s just a poor old man. His legs are grey. His ears are gnarled. His eyes and legs are old and bent. His ears are grizzled ...', 'remove-powered-by-wp');
                echo '</p>';

?>
<a href="https://webd.uk/product/remove-powered-by-wp-donation/" title="<?= __('Donate to "Remove Powered by Wordpress"', 'remove-powered-by-wp'); ?>" class="button-primary"><?= __('Donate to "Remove Powered by Wordpress"', 'remove-powered-by-wp'); ?></a>
<a href="#" id="rpbw-already-paid" title="<?= __('Aleady Donated!', 'remove-powered-by-wp'); ?>" class="button-primary"><?= __('Aleady Donated!', 'remove-powered-by-wp'); ?></a></p>

</div>

<script>
    jQuery(document).on('click', '#rpbw-already-paid', function() {
        if (confirm(<?= json_encode(__('Have you really? Press "Cancel" for a coupon code 🙂', 'remove-powered-by-wp')); ?>)) {
            alert('Thank you!');
            jQuery('.rpbw-notice').fadeTo(100, 0, function() {
                jQuery('.rpbw-notice').slideUp(100, function() {
                    jQuery('.rpbw-notice').remove()
                })
            })
            jQuery.ajax({
            	url: ajaxurl,
            	data: {
                	action: 'dismiss_rpbw_notice_handler',
            	    donated: 'true',
                	_ajax_nonce: '<?= wp_create_nonce('rpbw-ajax-nonce'); ?>'
            	}
        	});
        } else {
            alert('Use coupon code POWER for 50% discount 🙂');
            window.location.assign('https://webd.uk/product/remove-powered-by-wp-donation/');
        }
    });
    jQuery(document).on('click', '.rpbw-notice .notice-dismiss', function() {
    	jQuery.ajax({
    	    url: ajaxurl,
    	    data: {
        		action: 'dismiss_rpbw_notice_handler',
        		_ajax_nonce: '<?= wp_create_nonce('rpbw-ajax-nonce'); ?>'
    	    }
	    });
    });
</script>

<?php

            }
        }

        function rpbw_ajax_notice_handler() {

            check_ajax_referer('rpbw-ajax-nonce');
            $plugin_data = get_plugin_data(__FILE__);
            update_user_meta(get_current_user_id(), 'rpbw-notice-dismissed', $plugin_data['Version']);
            if (isset($_REQUEST['donated']) && $_REQUEST['donated'] == 'true' && current_user_can('manage_options')) {
				update_option('rpbw_purchased', true);
            }

        }

	}

    if (in_array(get_template(), remove_powered_by_wp_class::$compatible_themes, true)) {

    	$remove_powered_by_wp_object = new remove_powered_by_wp_class();

    } else {

        $themes = wp_get_themes();
        $compatible_theme_installed = false;

        foreach (remove_powered_by_wp_class::$compatible_themes as $key => $value) {

            if (isset($themes[$value]) && $themes[$value]) {

                $compatible_theme_installed = true;

            }

        }

        if (!$compatible_theme_installed) {

            add_action('admin_notices', 'rpbw_wrong_theme_notice');

        }

        add_action('after_setup_theme', 'rpbw_is_theme_being_previewed');

    }

    function rpbw_wrong_theme_notice() {

?>
<div class="notice notice-error">
<p><strong><?php _e('Remove "Powered by Wordpress" Plugin Error', 'remove-powered-by-wp'); ?></strong><br />
<?php
        printf(
            __('This plugin requires one of the default Wordpress themes to be active or live previewed in order to function. Your theme "%s" is not compatible. Please install and activate or live preview one of these themes (or a child theme thereof):', 'remove-powered-by-wp'),
            get_template()
        );

        $theme_list = array();

        foreach (remove_powered_by_wp_class::$compatible_themes as $key => $value) {

            $theme_list[] = '<a href="' . add_query_arg('search', $value, admin_url('theme-install.php')) . '" title="' .  __($key, 'remove-powered-by-wp') . '">' .  __($key, 'remove-powered-by-wp') . '</a>';

        }

        echo ' ' . implode(', ', $theme_list) . '.';

?></p>
</div>

<?php

    }

    function rpbw_activation() {

        set_theme_mod('remove_powered_by_wordpress', true);

    }
	register_activation_hook(__FILE__, 'rpbw_activation');

    function rpbw_is_theme_being_previewed() {

        global $compatible_theme_installed;

        if ($compatible_theme_installed && is_customize_preview()) {

            $remove_powered_by_wp_object = new remove_powered_by_wp_class();

        }

    }

}

?>

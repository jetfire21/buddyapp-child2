Дочерняя тема buddyapp-child
---------------------------------------------
#1 изменения в оригинальном плагине WP Job Manager Field Editor

заменил
get_attachment_id_from_url
a21_get_attachment_id_from_url
иначе выскакивает 500 ошибка на сайте!

meta_key=job_group_a21
wordpressactionhook Cause/Group

вывести все отладочные данные:
wp-admin/edit.php?post_type=job_listing&page=edit_job_fields&debug

/**
 * Plugin Name: WP Job Manager Field Editor
 * Plugin URI:  https://plugins.smyl.es/wp-job-manager-field-editor
 * Description: Full ajax plugin to Disable, Create, or Modify all WP Job Manager Fields, with numerous other advanced features.
 * Version:     1.6.0
 * Author:      Myles McNamara
 * Author URI:  http://plugins.smyl.es
 * Requires at least: 4.1
 * Tested up to: 4.6.1
 * Domain Path: /languages
 * Text Domain: wp-job-manager-field-editor
 * Last Updated: Tue Oct 04 2016 08:27:00
 */

удалил,чтобы не запрашивал ввода лицензионного ключа
/wp-content/plugins/wp-job-manager-field-editor/includes/updater/views/html-key-notice.php
 <div class="updated">
	<p class="smylesv2-updater-dismiss" style="float:right;"><a href="<?php echo esc_url( add_query_arg( 'dismiss-' . sanitize_title( $this->plugin_slug ), '1' ) ); ?>"><?php _e( 'Hide notice', 'wp-job-manager-field-editor' ); ?></a></p>
	<p><?php printf( __( '<a href="%1$s">Please activate your license key</a> in order to receive updates and support for "%2$s".', 'wp-job-manager-field-editor' ), admin_url( 'index.php?page=smyles-licenses' ), esc_html( $this->plugin_data['Name'] ) ); ?></p>
</div>

meta_key=_job_group_a21

var_dump(get_option( $this->plugin_slug . '_hide_key_notice' ));

private метод нельзя переопределить http://php.net/manual/ru/language.oop5.visibility.php
он доступен только из того класса,где обьявлен,иначе будет выводиться fatal error
[04-Apr-2017 23:12:28 UTC] PHP Fatal error:  Call to private method sMyles_Updater_v2::add_notice() from context '' in /home/jetfire/www/dugoodr2.dev/wp-content/themes/buddyapp-child/job_manager/wp-job-manager-groups/index.php on line 199
-------------------
 Yes, enable caching of all field configuration
This plugin uses WordPress transients to cache field configs to prevent excessive, and unecessary database queries. Whenever a new field is added, or updated, this cache is automatically purged and updated.. Disable this if you have issues with your custom filters not working correctly, or while debugging.

###########  go-fetch-jobs-wp-job-manager ########
кастомизировал этот плагин:
добавил след файлы
plugins/go-fetch-jobs-wp-job-manager/includes/phpQuery.php
/home/jetfire/www/dugoodr2.dev/wp-content/plugins/go-fetch-jobs-wp-job-manager/includes/curl.php
изменил:
/home/jetfire/www/dugoodr2.dev/wp-content/plugins/go-fetch-jobs-wp-job-manager/includes/class-gofetch-importer.php

###############
когда плагин total cache отключен браузеру отдается заголовок X-Robots-Tag:noindex, запрет индексации

###### bp-user-reviews
bp-user-reviews.php change version
plugins/bp-user-reviews/templates/review-list.php change
plugins/bp-user-reviews/templates/review-form.php change

######## WP-SpamShield  #####
этот плагин мешает корректо передавать email адресс в js ( например jQuery("input#signup_email").val("<?php echo $_GET['ve_email'];?>"); )
и снижает быстродействие сайта достаточно сильно
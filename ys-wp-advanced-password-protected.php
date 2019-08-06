<?php
/**
 * Plugin Name:     YS WP Advanced Password Protected
 * Description:     パスワード保護ページの拡張プラグイン。パスワード保護ページのタイトルに表示される「保護中」の削除、パスワード未入力ページにmoreタグより手前の文章を表示します。
 * Author:          yosiakatsuki
 * Author URI:      https://yosiakatsuki.net
 * Text Domain:     ys-wp-advanced-password-protected
 * Domain Path:     /languages
 * Version:         0.0.1
 *
 * @package         YS_WP_Advanced_Password_Protected
 */


/**
 * 「保護中」の削除
 *
 * @param string $format Format.
 *
 * @return string
 */
function YSWPAPP_protected_title_format( $format ) {
	return '%s';
}

add_filter( 'protected_title_format', 'YSWPAPP_protected_title_format' );


function YSWPAPP_the_password_form( $output ) {
	global $post;
	$post  = get_post( $post );
	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	/**
	 * more部分
	 */
	$more = '';
	/**
	 * 「このコンテンツはパスワードで保護されています。閲覧するには以下にパスワードを入力してください。」
	 * に変換される部分を退避
	 */
	$message = '<p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>';
	/**
	 * more手前を取得
	 */
	$content_arr = get_extended( $post->post_content );
	if ( ! empty( $content_arr['extended'] ) ) {
		$more    = $content_arr['main'];
		$message = '';
	}
	/**
	 * パスワード保護
	 */
	$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">' . $message . '<p><label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '" /></p></form>';

	return $more . $output;
}

add_filter( 'the_password_form', 'YSWPAPP_the_password_form' );

<?php
/*
Plugin Name: QQWorld Share
Plugin URI: http://project.qqworld.org
Description: Powerful share tools for SNS, MicroBlog, Blog, Bootmark, Mainly for China. 强大的SNS、微博客、博客、书签分享工具，主要用于中国网站。
Version: 1.2
Author: Michael Wang
Author URI: http://project.qqworld.org
*/

define('QQWORLD_SHARE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('QQWORLD_SHARE_PLUGIN_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/');
define('QQWORLD_SHARE_PLUGIN_SETTINGS', serialize(array(
	'weibo' => 'Share to Sina Weibo',
	"qzone" => 'Share to Qzone',
	"qq" => 'Share to QQ Friend',
	"facebook" => 'Share to Facebook',
	"twitter" => 'Share to Twitter',
	"linkedin" => 'Share to LinkedIn',
	"baidu" => 'Share to Baidu Hi',
	"google" => 'Share to Google+',
	"jianghu-taobao" => 'Share to Jianghu of Taobao',
	"sohu-t" => 'Share to Tsohu',
	"tianya" => 'Share to Tianya Community',
	"t-163" => 'Share to T.163',
	"pengyou" => 'Share to Penyou',
	"tencent_weibo" => 'Share to T.tencent',
	"douban" => 'Share to Douban',
	"kaixin001" => 'Share to kaixin001',
	"renren" => 'Share to renren'
)));
define('QQWORLD_SHARE_PLUGIN_THTMES', serialize(array(
	'qqworld' => 'QQWorld',
	'wood' => 'Wood',
	'dark-metal' => 'Dark Metal',
	'stone' => 'Stone',
	'red-earth' => 'Red Earth',
	'blueprint' => 'Blueprint',
	'light-metal' => 'Light Metal',
	'jiathis' => 'JiaThis'
)));
define('QQWORLD_SHARE_PLUGIN_DEFAULT_SETTINGS', serialize(array('weibo', "qzone","qq","facebook","twitter","linkedin","baidu","google","jianghu-taobao","sohu-t","tianya","t-163","pengyou","tencent_weibo","douban","kaixin001","renren")));
define('QQWORLD_SHARE_PLUGIN_DEFAULT_THTME', 'qqworld');

class qqworld_share {
	var $shareTo;
	var $theme;
	public function __construct() {
		$this->get_share_meta();
		add_action( 'plugins_loaded', array($this, 'load_language') );
		add_action( 'admin_menu', array($this, 'create_menu') );
		add_action( 'admin_init', array($this, 'init') );
		add_action( 'wp_enqueue_scripts', array($this, 'add_style') );
		add_filter( 'the_content', array($this, 'add_share') );
		add_filter( 'plugin_row_meta', array($this, 'registerPluginLinks'),10,2 );
	}
	function registerPluginLinks($links, $file) {
		$base = plugin_basename(__FILE__);
		if ($file == $base) {
			$links[] = '<a href="' . menu_page_url( 'qqworld_share', 0 ) . '">' . __('Settings') . '</a>';
		}
		return $links;
	}
	public function load_language() {
		load_plugin_textdomain( 'qqworld_share', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	public function init() {
		register_setting('qqworld-share', 'qqworld-share-settings');
		register_setting('qqworld-share', 'qqworld-share-theme');
		register_setting('qqworld-share', 'qqworld-share-posttypes');
	}
	public function create_menu() {
		add_submenu_page('options-general.php', __('QQWorld Share Settings', 'qqworld_share'), __('QQWorld Share', 'qqworld_share'), 'administrator', 'qqworld_share', array($this, 'fn') );
	}
	public function fn() {
?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e('QQWorld Share Settings', 'qqworld_share')?></h2>
		<p><?php _e('Activate this plug-in, will automatically display the following share button in the bottom of posts content or pages content.', 'qqworld_share'); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields('qqworld-share'); ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="style"><?php _e('Share button icon style:', 'qqworld_share'); ?></label></th>
						<td>
						<?php
						foreach (unserialize(QQWORLD_SHARE_PLUGIN_THTMES) as $key => $theme) :
							wp_register_style('qqworld_share_'.$key, QQWORLD_SHARE_PLUGIN_URL. 'style/'.$key.'.css');
							wp_enqueue_style('qqworld_share_'.$key);
							$checked = $key == $this->theme ? ' checked="checked"' : '';
						?>
							<p><input name="qqworld-share-theme" type="radio" id="<?php echo $key; ?>" value="<?php echo $key; ?>"<?php echo $checked; ?> /> <label for="<?php echo $key; ?>"><?php _e($theme, 'qqworld_share'); ?></label></p>
							<?php echo $this->add_share('', $key) . '<hr />';
						endforeach; ?>
						<p class="description"></p></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="buttons"><?php _e('Select the share button you want to use:', 'qqworld_share'); ?></label></th>
						<td>
							<select name="qqworld-share-settings[]" id="buttons" multiple="multiple" size="15">
							<?php
							foreach (unserialize(QQWORLD_SHARE_PLUGIN_SETTINGS) as $key => $share ):
								$selected = in_array($key, $this->shareTo) ? ' selected="selected"' : '';
							?>
								<option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php _e( $share, 'qqworld_share' ); ?></option>
							<?php
							endforeach;
							?>
							</select>
							<p class="description"><?php _e('Hold down the Ctrl key to multi-select.', 'qqworld_share')?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="buttons"><?php _e('Select the post type:', 'qqworld_share'); ?></label></th>
						<td>
							<?php
							$post_types = get_post_types( '', 'object' ); 
							foreach ( $post_types as $post_type ) :
								$checked = in_array($post_type->name, get_option('qqworld-share-posttypes', array('post', 'page'))) ? ' checked="checked"' : '';
							?>						
								<label><input name="qqworld-share-posttypes[]" value="<?php echo $post_type->name; ?>" type="checkbox"<?php echo $checked; ?> /> <?php echo $post_type->labels->name; ?></label>
							<?php endforeach; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
	}
	public function get_share_meta() {
		$this->theme = get_option('qqworld-share-theme', QQWORLD_SHARE_PLUGIN_DEFAULT_THTME);
		$this->shareTo = is_array(get_option('qqworld-share-settings', QQWORLD_SHARE_PLUGIN_DEFAULT_SETTINGS)) ? get_option('qqworld-share-settings', QQWORLD_SHARE_PLUGIN_DEFAULT_SETTINGS) : unserialize(get_option('qqworld-share-settings', QQWORLD_SHARE_PLUGIN_DEFAULT_SETTINGS));
	}
	public function add_style() {
		if ( is_singular( array('post','page') ) ) {
			wp_register_style('qqworld_share', QQWORLD_SHARE_PLUGIN_URL. 'style/'.$this->theme.'.css');
			wp_enqueue_style('qqworld_share');
			wp_register_script('qqworld_share', QQWORLD_SHARE_PLUGIN_URL. 'js/share.js', 'jquery');
			wp_enqueue_script('qqworld_share');
			$pic = $this->get_pics();
			if (is_array($pic)) 'new Array("' . implode('","', $pic) . '")';
			$translation_array = array(
				'source' => urlencode(get_bloginfo('name')),
				'source_url' => urlencode(get_bloginfo('url')),
				'title' => urlencode(get_the_title()),
				'url' => urlencode(get_permalink()),
				'summary' => urlencode(get_the_excerpt()),
				'pic' => $pic
			);
			wp_localize_script('qqworld_share', 'qqworld_share', $translation_array, '1.0.0');
		}
	}

	public function get_pics() {
		global $post;
		$pic = array();
		$args = array(
			'post_parent' => get_the_ID(),
			'post_type' => 'attachment',
			'post_mime_type' => 'image'
		);
		$images = get_children( $args );
		if (!empty($images) )
			foreach ($images as $image)
				array_push($pic, $image->guid);
		else {
			$full_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
			$pic = $full_image[0];
		}
		return $pic;
	}

	public function add_share($content, $theme = Null) {
		global $post;
		if ( is_admin() || is_array($this->shareTo) && count($this->shareTo) > 0
			&& ( is_singular( get_option('qqworld-share-posttypes', array('post', 'page')) )
			&& !is_preview() ) ) {
			$theme = empty($theme) ? $this->theme : $theme;
			ob_start();
	?>
			<div class="qqworld-share-container <?php echo $theme; ?>">
				<div class="title"><?php _e('Like this article you may wish to share with my friends now!', 'qqworld_share'); ?></div>
				<ul>
					<li class="more" title="<?php _e('Show more.', 'qqworld_share'); ?>"></li>
					<?php foreach ($this->shareTo as $key) :
						$settings = unserialize(QQWORLD_SHARE_PLUGIN_SETTINGS);
					?>
					<li class="<?php echo $key; ?>" title="<?php _e( $settings[$key], 'qqworld_share' ); ?>"></li>
					<?php endforeach; ?>
					<li class="clear"></li>
				</ul>
			</div>
	<?php
			$output = ob_get_contents();
			ob_clean();
			return $content . $output;
		} else return $content;		
	}
}
new qqworld_share;
?>
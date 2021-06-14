<?php
/**
 * Created by mb.ideas.
 * User: pupunzi
 * Date: 19/11/16
 * Time: 15:07
 */

if (!class_exists("mbideas_notice")) {

	class mbideas_notice
	{

		public function __construct($name_space, $plugin_file, $version = 0)
		{
			$this->name_space = $name_space;
			$this->notices = array();
			$this->plugin_file = $plugin_file;
			$this->id = 0;
			$this->version = $version;

			add_action('admin_enqueue_scripts', function () {
				global $name_space;
				self::load_mb_admin_script($name_space);
			});

			add_action('wp_ajax_mb_ignore_notice', array('mbideas_notice', 'ignore_notice'));

			add_action('admin_notices', function () {
				self::print_notice();
			});

			add_action('admin_init', function () {
				global $name_space;
				register_setting($name_space . '-settings-group', $name_space . '_notice_dismiss');
			});

			register_activation_hook($plugin_file, function () {
				global $name_space;
				add_option($name_space . '_notice_dismiss', '[]');
			});

		}

		public function add_notice($mbtg_message, $class)
		{
			array_push($this->notices, array("id" => $this->name_space . $this->id . "_" . $this->version, "message" => $mbtg_message, "class" => $class));
			$this->id++;
		}

		public function print_notice()
		{

			$opt = get_option($this->name_space . '_notice_dismiss');
			$dismissed_notices = "";
			if (isset($opt) && !is_array($opt))
				$dismissed_notices = json_decode($opt, false);

			foreach ($this->notices as $notice) {
				if (!is_array($dismissed_notices) || !in_array($notice["id"], $dismissed_notices))
					echo '<div data-namespace ="' . $this->name_space . '" id="' . $notice["id"] . '" class="notice-' . $notice["class"] . ' ' . $this->name_space . '-notice notice is-dismissible"><p>' . $notice["message"] . ' </p></div>'; //<a href="?my-plugin-ignore-notice">Dismiss</a>
			}
		}

		public function ignore_notice()
		{
			$name_space = sanitize_text_field($_POST["name_space"]);
			$notice_id = sanitize_text_field($_POST["notice_id"]);

			$dismissed_notices = json_decode(get_option($name_space . '_notice_dismiss'), false);

			if (!is_array($dismissed_notices))
				$dismissed_notices = array();

			array_push($dismissed_notices, $notice_id);

			update_option($name_space . '_notice_dismiss', json_encode($dismissed_notices, true));

			exit(json_encode(array("notice_id" => $notice_id, "dismissed" => $dismissed_notices), true));
		}


		public function reset_notice()
		{
			update_option($this->name_space . '_notice_dismiss', '[]');
		}

		public function load_mb_admin_script($name_space)
		{
			wp_register_script('mbideas_notice', plugins_url('mb.notice.js', __FILE__), array('jquery'), "1.0", true, 1000);
			$data = array(
			  "name_space" => $name_space
			);
			wp_localize_script('mbideas_notice', 'mb_obj', $data);
			wp_enqueue_script('mbideas_notice');
		}
	}
}

<?php

namespace ThemeSkeleton;

use Symfony\Component\Yaml\Yaml;

define('MY_ACF_PATH', get_stylesheet_directory() . '/vendor/advanced-custom-fields/advanced-custom-fields-pro/');
define('MY_ACF_URL', get_stylesheet_directory_uri() . '/vendor/advanced-custom-fields/advanced-custom-fields-pro/');

class Theme
{

  /**
   * contains information from config/config.yml
   *
   * @var array $config
   */
  private $config;

  /**
   * @var Theme $instance
   */
  private static $instance;

  public static function getInstance()
  {
    return self::$instance;
  }

  public function init()
  {
    self::$instance = $this;

    if (!class_exists('acf')) {
      // Plugin path to the included ACF Pro plugin
      include_once(MY_ACF_PATH . 'acf.php');
    }

    $this->config = Yaml::parse(file_get_contents(get_stylesheet_directory() . "/config/config.yml"), Yaml::PARSE_OBJECT_FOR_MAP);

    add_filter('acf/settings/load_json', array($this, 'my_acf_json_load_point'));
    add_filter('acf/settings/save_json', array($this, 'my_acf_json_save_point'));

    // Customize the url setting to fix incorrect asset URLs.
    add_filter('acf/settings/url', array($this, 'my_acf_settings_url'));

    // (Optional) Hide the ACF admin menu item.
    add_filter('acf/settings/show_admin', array($this, 'my_acf_settings_show_admin'));

    // add shortcodes
    add_shortcode('acf_wrapper', array(ACFWrapper::class, 'render'));

    // add support for images in blogposts
    add_theme_support('post-thumbnails', array('post'));

    $this->activate();

    if (is_admin()) {
      $backend = new Backend($this);
      $backend->init();
    }

  }

  public function getConfig()
  {
    return $this->config->config;
  }

  private function activate()
  {
    // put custom code here

  }


  public function my_acf_json_load_point($paths)
  {
    // remove original path (optional)
    unset($paths[0]);

    // append path
    $paths[] = get_stylesheet_directory() . '/config/fieldgroups';

    // return
    return $paths;
  }

  public function my_acf_json_save_point($path)
  {
    // update path
    $path = get_stylesheet_directory() . '/config/fieldgroups';

    // return
    return $path;
  }

  public function my_acf_settings_url($url)
  {
    return MY_ACF_URL;
  }

  public function my_acf_settings_show_admin($show_admin)
  {
    return $this->getConfig()->admin->show_acf;
  }

}

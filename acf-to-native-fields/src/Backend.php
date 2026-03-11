<?php


namespace ThemeSkeleton;


use Symfony\Component\Yaml\Yaml;

class Backend
{
  /**
   * @var Theme|null
   */
  private $theme = null;

  /**
   * Backend constructor.
   * @param $theme Theme
   */
  public function __construct($theme)
  {
    $this->theme = $theme;
  }

  public function acf_enabled()
  {
    if (is_admin() && !class_exists('acf')) {
      add_action('admin_notices', array($this, 'missing_acf_plugin_notice'));

      deactivate_plugins(plugin_basename(__FILE__));

      if (isset($_GET['activate'])) {
        unset($_GET['activate']);
      }
    }
  }

  public static function hide_editor()
  {
    remove_post_type_support('page', 'editor');
    remove_post_type_support('idea', 'editor');
    remove_post_type_support('contacts', 'editor');
  }

  private function missing_acf_plugin_notice()
  {
    print '<div class="error"><p>Sorry, but this theme requires the ACF plugin to be installed and active, you might encourage strange behaviour until you install and activate ACF</p></div>';
  }

  public function init()
  {
    if ($this->theme->getConfig()->site->create_pages) {
      add_action('init', array($this, 'check_pages'));
    }
    if ($this->theme->getConfig()->admin->hide_editor) {
      add_action('admin_init', array($this, 'hide_editor'));
    }
    add_action('admin_init', array($this, 'acf_enabled'));
  }

  public static function check_pages()
  {
    try {
      $pages = Yaml::parse(file_get_contents(get_stylesheet_directory() . "/config/pages.yml"), Yaml::PARSE_OBJECT_FOR_MAP);
      foreach ($pages as $page => $options) {
        $check_page_exist = get_page_by_title($options->title, 'OBJECT', 'page');
        if (empty($check_page_exist)) {
          $post = array(
            'comment_status' => 'close',
            'ping_status' => 'close',
            'post_author' => 1,
            'post_title' => $options->title,
            'post_name' => strtolower(str_replace(' ', '-', trim($page))),
            'post_status' => 'publish',
            'post_type' => 'page',
            'menu_order' => $options->order
          );
          $parent_id = wp_insert_post($post);
          if ($page === "home") {
            $homepage = get_page($parent_id);
            if ($homepage) {
              update_option('page_on_front', $homepage->ID);
              update_option('show_on_front', 'page');
            }
          }
        }
        if (is_object($options->pages)) {
          foreach ($options->pages as $subpage => $suboptions) {
            $check_subpage_exist = get_page_by_title($suboptions->title, 'OBJECT', 'page');
            if (empty($check_subpage_exist)) {
              $post = array(
                'comment_status' => 'close',
                'ping_status' => 'close',
                'post_author' => 1,
                'post_title' => $suboptions->title,
                'post_name' => strtolower(str_replace(' ', '-', trim($subpage))),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_parent' => $parent_id,
                'menu_order' => $options->order
              );
              wp_insert_post($post);
            }
          }
        }
      }
    } catch (ParseException $exception) {
      printf('Unable to parse the YAML string: %s', $exception->getMessage());
    }
  }

}

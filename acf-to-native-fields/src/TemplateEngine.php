<?php

namespace ThemeSkeleton;

class TemplateEngine
{

  public static function render($templateName, $options)
  {
    $path = plugin_dir_path(__FILE__) . '../resources/templates/' . $templateName . '.php';
    ob_start();
    require($path);
    return ob_get_clean();
  }

}

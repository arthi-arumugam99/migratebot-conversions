<?php


namespace ThemeSkeleton;

class ACFWrapper extends Shortcode
{

  public static function render($atts, $content, $tag)
  {
    return TemplateEngine::render('partials/' . $atts['partial'], []);
  }
}

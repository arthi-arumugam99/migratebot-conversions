<?php
require __DIR__ . '/vendor/autoload.php';

use ThemeSkeleton\Theme;

$theme = new Theme();
$theme->init();

if ($theme->getConfig()->release->check) {
  $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    $theme->getConfig()->release->repository,
    __FILE__,
    $theme->getConfig()->theme->slug
  );

  $myUpdateChecker->getVcsApi()->enableReleaseAssets();
  $myUpdateChecker->setAuthentication($theme->getConfig()->release->token);
}


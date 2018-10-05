<?php
// CRONJOB REGISTER
if (rex_addon::get('cronjob')->isAvailable()) {
	rex_cronjob_manager::registerType('rex_cronjob_project_manager_data');
  rex_cronjob_manager::registerType('rex_cronjob_project_manager_favicon');
}

// CSS und JavaScript einbinden

if (rex::isBackend() && is_object(rex::getUser())) {
    //rex_view::addJsFile($this->getAssetsUrl('js/project-manager-server.js'));
}

if (rex::isBackend() && is_object(rex::getUser())) {
//  set as start page
  rex_extension::register('PAGES_PREPARED', function (rex_extension_point $ep) {
    $pages = $ep->getSubject();
    $pages[$this->getAddOn()->getName()]->setHref(rex_url::backendPage($this->getProperty('package')));
    $ep->setSubject($pages);
  }, rex_extension::LATE);
}


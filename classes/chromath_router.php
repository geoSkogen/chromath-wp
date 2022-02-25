<?php

class Chromath_Router {

  protected $subdomain;
  protected $templates_path;
  protected $favicon_filename;

  public function __construct($subdomain) {
    $this->subdomain = $subdomain;
    $this->templates_path = __DIR__ . '../../templates/';
  }

  public function get($uri) {

    $resource = str_replace($this->subdomain,'',$uri);

    switch($resource) {

      case '/' :

        if (!class_exists('Chromath_Home_Template')) {
          include_once $this->templates_path . 'chromath_home_template.php';
        }
        $app_html = new Chromath_Home_Template();
        break;

      default :
        if (!class_exists('Chromath_Home_Template')) {
          include_once $this->templates_path . 'chromath_default_template.php';
        }
        $app_html = new Chromath_Default_Template();
    }

    return $app_html->app_html();

  }
}

?>

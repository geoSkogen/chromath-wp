<?php

class Chromath_Router {

  protected $subdomain;
  protected $templates_path;
  protected $favicon_filename;

  public function __construct($subdomain,$favicon_filename) {
    $this->subdomain = $subdomain;
    $this->templates_path = __DIR__ . '../../templates/';
    $this->favicon_filename = $favicon_filename;
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

  public function favicon_tag() {
    // void - echo
    $uri_arr = explode('/',$_SERVER['REQUEST_URI']);
    if (in_array($this->subdomain,$uri_arr)) {

      $href = site_url() . '/wp-content/plugins/chromath/records/images/' . $this->favicon_filename;
      $tag = "<link rel='icon' href='{$href}' type='image/x-icon' />";
      error_log($href);
      echo $tag;
    }
  }

}

?>

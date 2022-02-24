<?php

class Chromath_Templater {

  public  $script_handles;
  public  $style_handles;
  protected $router;
  protected $theme_handle;

  public function __construct($router, $scripts_arr, $styles_arr, $theme_style_handle) {
    $this->script_handles = $scripts_arr;
    $this->style_handles = $styles_arr;
    $this->router = $router;
    $this->theme_handle = $theme_style_handle;
    //
    add_shortcode( 'chromath_template',
      [$this,'print_chromath_template']
    );
    //
    add_action('wp_enqueue_scripts',[$this,'add_assets']);
  }


  public function add_assets() {
    // void - register
    foreach ($this->style_handles as $style_handle) {
      wp_register_style(
        $style_handle,
        plugin_dir_url(__FILE__) .
        '../style/' . 'chromath_templater_' . $style_handle . '_style.css'
      );
    }

    //
    foreach ($this->script_handles as $script_handle) {
      wp_register_script(
        $script_handle,
        plugin_dir_url(__FILE__) .
        '../lib/' . 'chromath_templater_' . $script_handle . '_script.js',
        array(),
        null,
        true
      );
    }
    //
  }


  public function print_chromath_template($atts = []) {
    // void - echo
    extract(shortcode_atts(array(
      'style_slugs' => '',
      'script_slugs' => ''
     ), $atts));
    //
    $style_slugs = !empty($atts['style_slugs']) ? explode(',',$atts['style_slugs']) : [];
    $script_slugs = !empty($atts['script_slugs']) ? explode(',',$atts['script_slugs']) : [];
    //
    wp_dequeue_style($this->theme_handle);
    wp_deregister_style($this->theme_handle);
    wp_enqueue_style(
      'font-awesome-5',
      'https://use.fontawesome.com/releases/v5.3.0/css/all.css',
      array(),
      '5.3.0'
    );
    // main stylesheet is always enqueued -
    wp_enqueue_style('main');
    // stylesheet args option -
    foreach($style_slugs as $style_slug) {
      if (in_array($style_slug, $this->style_handles) ) {
        wp_enqueue_style($style_slug);
      }
    }
    // javascript doc args option -
    foreach($script_slugs as $script_slug) {
      if ( in_array($script_slug, $this->script_handles) ) {
        wp_enqueue_script($script_slug);
      }
    }
    // show the shortcode the path
    if (strpos(site_url(),'localhost')) {
      // kludge against LAMP staging site URL
      $url_arr= explode('/',site_url());
      $domain = $url_arr[count($url_arr)-1] ?
        $url_arr[count($url_arr)-1] : $url_arr[count($url_arr)-2];
      //
      $path = str_replace('/' . $domain . '/', '', $_SERVER['REQUEST_URI']);
    } else {
      //
      $path = substr(1,$_SERVER['REQUEST_URI']);
    }
    // print the routed app template onto the page
    echo $this->router->get($path);
  }

}

<?php

class Chromath_Admin {

  protected $options;
  protected $suboptions;

  public $style_handles;
  public $script_handles;

  public function __construct($script_handles,$style_handles) {

    $this->style_handles = $style_handles;
    $this->script_handles = $script_handles;

    //
    add_action(
     'admin_menu',
     [$this,'chromath_register_options_pages']
    );
    //
    add_action(
      'admin_init',
      [$this,'chromath_init_settings_api']
    );
    //
    add_action('wp_admin_enqueue_scripts',[$this,'add_assets']);
    //
  }


  public function add_assets() {
    //
    foreach ($this->style_handles as $style_handle) {
      wp_register_style(
        $style_handle,
        plugin_dir_url(__FILE__) .
        '../style/' . 'chromath_admin_' . $style_handle . '_style.css'
      );
    }
    //
    foreach ($this->script_handles as $script_handle) {
      wp_register_script(
        $script_handle,
        plugin_dir_url(__FILE__) .
        '../lib/' . 'chromath_admin_' . $script_handle . '_script.js',
        array(),
        null,
        true
      );
    }
    //
  }


  public function chromath_register_options_pages () {
    //
    add_menu_page(
      'Chromath Options', // Page Title
      'Chromath',  // Menu Title
      'manage_options', //capability
      'chromath',  //menu_slug
      [$this,'chromath_options_page'],//cb function
      'dashicons-flag',
      24
    );
    //
    add_submenu_page(
      'chromath', // parent slug
      'Chromath Sub-Options', // Page Title
      'Chromath Sub-Options',  // Menu Title
      'manage_options', //capability
      'chromath_suboptions',  //menu_slug
      [$this,'chromath_suboptions_page']//cb function
    );
  }


  public function chromath_init_settings_api() {
    //
    add_settings_section(
      'chromath',         //unique id
      'Chromath Options Section',         //title
      [$this,'chromath_options_section'],    //call back function
      'chromath'        //page_slug
    );
    //
    add_settings_field(
      'api_key', //id
      'Chromath API Key', //label
      [$this,'chromath_api_key_field'],    //call back function
      'chromath',    // page slug
      'chromath'     //section (parent settings-section uniqueID)
    );
    //
    add_settings_field(
      'publish', //id
      'Chromath Publish Now?', //label
      [$this,'chromath_publish_field'],    //call back function
      'chromath',    // page slug
      'chromath'     //section (parent settings-section uniqueID)
    );
    //
    add_settings_section(
      'chromath_suboptions',         //unique id
      'Chromath Suboptions Section',         //title
      [$this,'chromath_suboptions_section'],    //call back function
      'chromath_suboptions'        //page_slug
    );
    //
    add_settings_field(
      '1', //id
      'Chromath Suboption 1', //label
      [$this,'chromath_suboption_1_field'],    //call back function
      'chromath_suboptions',    // page slug
      'chromath_suboptions'     //section (parent settings-section uniqueID)
    );

    register_setting(
      'chromath',
      'chromath'
    );

    register_setting(
      'chromath_suboptions',
      'chromath_suboptions'
    );
  }


  protected function collect_section_overhead($prop_slug,$db_slug,$path_slug) {
    //
    $db_slug = ($db_slug) ? '_' . $db_slug : '';
    //
    $this->{$prop_slug} =
      !empty( get_option('chromath' . $db_slug) ) ?
        get_option('chromath' . $db_slug) : [];
    //
    if (in_array($path_slug,$this->style_handles)) {
      wp_enqueue_style($path_slug);
    }
    //
    if (in_array($path_slug,$this->script_handles)) {
      wp_enqueue_script($path_slug);
    }
  }


  public function chromath_options_section() {
    //
    $this->collect_section_overhead('options','','main');
    //error_log('settings overhead call');
    //error_log(print_r($this->options,true));
    //
    ?>
    <div class="chromath-signal">
      This is the Options Section of the Options Page
    </div>
    <?php
    //
  }


  public function chromath_api_key_field() {
    //
    $val = !empty($this->options['api_key']) ? $this->options['api_key'] : '';
    $att = ($val) ? 'value' : 'placeholder';
    $val = ($val) ? $val : 'not set';
    //
    ?>
    <label for="chromath_api_key">API Key:</label>
    <input type="text" id="api-key" class="chromath-admin"
     name="chromath[api_key]" <?php echo $att ."='". $val  ."'"?> />
    <?php
    //
  }


  public function chromath_publish_field() {
    //
    $val = (!empty($this->options['publish']) && $this->options['publish']) ?
      $this->options['publish'] : '';

    if ($val) {
      //error_log('publish value is set');
      if (!class_exists('Chromath_Publisher')) {
        include_once 'chromath_publisher.php';
      }
      //
      $publisher = new Chromath_Publisher('chromath');
      //
      if (!$publisher->error) {
        //error_log('valid data for publication');
        $publisher->publish('chromath','templates/template-full-width.php');

        $opts = get_option('chromath');
        $opts['publish'] = 0;
        update_option('chromath',$opts);
      }
      //
      if ($publisher->error) {
        error_log(print_r($publisher->error,true));
      }
    }
    //error_log('the latest options fetch--should include both api and publish props');
    //error_log(print_r($this->options,true));
    ?>
    <label for="chromath_publish_checkbox">Publish Now?</label>
    <input type="checkbox" id="publish" class="chromath-checkbox"
      name="chromath[publish]" value="1" />
    <?php
    //
  }


  public function chromath_suboptions_section () {
    //
    $this->collect_section_overhead('suboptions','suboptions','main');
    //
    ?>
    <div class="chromath-signal">
      This is the Sub-Options Section of the Sub-Options Page
    </div>
    <?php
    //
  }


  public function chromath_suboption_1_field() {
    //
    $val = !empty($this->suboptions[1]) ? $this->suboptions[1] : '';
    $att = ($val) ? 'value' : 'placeholder';
    $val = ($val) ? $val : 'not set';
    //
    ?>
    <label for="chromath_suboption_1"></label>
    <input type="text" id="suboption-1" class="chromath-admin"
     name="chromath_suboptions[1]" <?php echo $att ."='". $val  ."'"?> />
    <?php
    //
  }


  public function chromath_options_page () {
    //
    $this->chromath_options_form('chromath');
  }


  public function chromath_suboptions_page () {
    //
    $this->chromath_options_form('chromath_suboptions');
  }


  protected function chromath_options_form ($prop) {
    //
    echo "<form method='POST' action='options.php' id='$prop'>";
    //
    settings_fields( $prop );
    do_settings_sections( $prop );
    submit_button();
    //
    echo '</form>';
  }

}

?>

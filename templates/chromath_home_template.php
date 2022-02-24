<?php

class Chromath_Home_Template {

  public function __construct() {
    error_log('throw template');
  }

  public function app_html() {

    error_log('this is app the shell');
    ob_start();
    ?>
    <h3>Chromath TestPattern</h3>
    <div id="chromath-style-render"></div>
    <div id="chromath-script-render"></div>
    <h2>You have reached the Chromath Home template.  To keep building:</h2>
    <h3>
      <ul>
        <li>Add a case statement to the router switch.</li>
        <li>Create a corresponding template object, and . . .</li>
        <li>write an app_html() method for it which returns your markup.</li>
        <li>Include the new object's filepath and instatiate the object in that case's codeblock.</li>
      </ul>
    </h3>
    <?php

    $html = ob_get_clean();

    return $html;
  }

}

?>

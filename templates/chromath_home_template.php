<?php

class Chromath_Home_Template {

  public function __construct() {
    error_log('throw template');
  }

  public function app_html() {

    error_log('this is app the shell');
    ob_start();
    ?>
    <div class="flexOuterCenter">
  <div id="app">
    <div class="flexOuterCenter">
      <div id="backdrop"></div>
    </div>
    <!-- THIS IS A MODEL OF THE RESULTS MODAL -->
    <div class="flexOuterCenter">
      <div class="modal" id="resultsBox">
        <div class="relShell">
          <div class="closeModal" id="closeResults">&times;</div>
          <!--<div class="absoluteText" id="resultsSymbol">&equals;</div>-->
        </div>
        <div class="flexOuterCenter" id="resultsText">
        </div>
      </div>
    </div>
    <!-- THIS IS A MODEL OF THE SUBTOTAL PANEL MODAL -->
    <div class="flexOuterCenter">
      <div class="modal" id="subTotalBox">
        <div class="relShell">
          <div class="closeModal" id="closeSubTotal">&times;</div>
        </div>
        <div class="flexOuterCenter" id="subTotalText">
        </div>
      </div>
    </div>
    <!-- THIS IS A MODEL OF THE CONTROL PANEL MODAL -->
    <div class="flexOuterCenter">
      <div class="modal" id="controlPanelBox">
        <div class="relShell">
          <div class="openSubModal">
            <div class="customHR"></div>
            <div class="customHR"></div>
            <div class="customHR"></div>
          </div>
          <div class="subModal" id="controlPanelHeader">
            <div class="relShell">
              <div class="closeSubModal">&times;</div>
            </div>
            <div class="flexOuterCenter" id="controlPanelNav">
              <div class="clearButtonSmall_noLink">start</div>
              <div class="clearButtonSmall">
                <a class="clearButtonLink" href="pages/view1.html">version1</a>
              </div>
              <div class="clearButtonSmall">
                <a class="clearButtonLink"
                   href="https://github.com/geoSkogen/chromathagraphic/">
                   source
                </a>
              </div>
              <div class="clearButtonSmall">
                <a class="clearButtonLink"
                   href="https://geoskogen.github.io/josephscoggins/">
                   home
                </a>
              </div>
            </div>
          </div>
          <div class="closeModal" id="closeControlPanel">&times;</div>
        </div>
        <div class="flexOuterCenter" id="controlPanelText">
          <form>
            <div class="flexOuterCenter" id="controlPanelTitle">
              <div class="smallTitle">
                <span class="titleSpan">chromathagraphic</span>
                &nbsp;&ndash;v
                <span class="biggerSymbol">&raquo;</span>
                <span class="biggerNumber">2.0</span>
              </div>
            </div>
            <div class="flexOuterCenter" id="formShell0">
              <div class="radioLabel">mode</div>
            </div>
            <div class="flexOuterCenter" id="formShell1">
              <input type="radio" name="mode" class="modeButton"
                 value="OrderOfOperations" id="OrderOfOperations"/>
              <input type="radio" name="mode" class="modeButton"
                 value="LineByLineSubTotal" id="LineByLineSubTotal"/>
            </div>
            <div class="flexOuterCenter" id="formShell2">
              <div class="radioButtonLabel">OrderOfOperations</div>
              <div class="radioButtonLabel">LineByLineSubTotal</div>
            </div>
            <div class="flexOuterCenter" id="formShell3">
              <div class="clearButton">clear</div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- THIS IS A MODEL OF THE NEW COLOR-CODED DISPLAY -->
    <div class="flexOuterCenter">
      <div class="colorCodeNumeric" id="textBox">
        <div class="flexOuterColumn" id="theOutput">

          <div class="flexInnerColumn">
            <div class="perspectiveShell_awayTop">
              <div class="flexOuterSpaceBetween">
                <div class="flexOuterStart">
                </div>
                <div class="flexOuterEnd">
                </div>
              </div>
            </div>
          </div>

          <div class="flexInnerColumn">
            <div class="perspectiveShell_awayBottom">
              <div class="flexOuterSpaceBetween">
                <div class="flexOuterStart">
                </div>
                <div class="flexOuterEnd">
                </div>
              </div>
            </div>
          </div>

          <div class="flexInnerColumn">
            <div class="perspectiveShell_relaxed">
              <div class="flexOuterSpaceBetween">
                <div class="flexOuterStart">
                </div>
                <div class="flexOuterEnd">
                </div>
              </div>
            </div>
          </div>

          <div class="flexInnerColumn">
            <div class="perspectiveShell_basic">
              <div class="flexOuterSpaceBetween">
                <div class="flexOuterStart" id="currentOperator">
                </div>
                <div class="flexOuterEnd" id="currentLine">
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!--  THIS IS A MODEL OF THE 10-KEY -->
    <div id="tenKey">
      <div class="flexInnerCenter" id="tenKeyLeft">
        <div class="flexOuterSpace">
          <div class="keyChar" id="laquo">&#171;</div>
          <div class="keyChar" id="divide">&#247;</div>
          <div class="keyChar" id="times">&#215;</div>
          <div class="keyChar">7</div>
          <div class="keyChar">8</div>
          <div class="keyChar">9</div>
          <div class="keyChar">4</div>
          <div class="keyChar">5</div>
          <div class="keyChar">6</div>
          <div class="keyChar">1</div>
          <div class="keyChar">2</div>
          <div class="keyChar">3</div>
          <div class="keyCharLong">0</div>
          <div class="keyChar" id="period">&#46;</div>
        </div>
        <div id="tenKeyRight">
          <div class="flexOuterColumn">
            <div class="keyChar" id="minus">&#8722;</div>
            <div class="keyCharTall" id="plus">&#43;</div>
            <div class="keyCharTall" id="equals">&#61;</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    <?php

    $html = ob_get_clean();

    return $html;
  }

}

?>

'use strict'

window.addEventListener("load", initFuncs)

function initFuncs() {
/*
//
// this is the DOM/view concern
//
*/
//adds click event to tenKey HTML elements
 function findKeys() {
   var classNames = ["keyChar","keyCharLong","keyCharTall"]
   var keys = []
   var classedElms = []
   var tenKeyArg = ""
   for (let i = 0; i < classNames.length; i++) {
     classedElms = document.getElementsByClassName(classNames[i])
     for (let ii = 0; ii < classedElms.length; ii++) {
         if (Number(classedElms[ii].innerHTML) ||
             classedElms[ii].innerHTML === "0") {
           classedElms[ii].id = "_" + classedElms[ii].innerHTML
         }
         classedElms[ii].addEventListener("click",function () {
           tenKeyArg = isNaN(this.innerHTML) ? this.id : this.innerHTML
           if (this.id === "period") { tenKeyArg = "." }
           initTenKey(tenKeyArg, true)
       })
       keys.push(classedElms[ii])
     }
   }
   return keys
 }

 function clickToClose(elmToClick,elmToClose) {
   elmToClick.addEventListener("click", function () {
     elmToClose.style.display = "none"
   })
 }

  function displayControlPanel() {
    controlPanel.style.display = "block"
    nav.style.display = "none"
    openNav.style.display = "block"
    controlPanelConsole.style.opacity = "1"
  }

  function displayResults(num, idStr, elm) {
    var charArr = num.toString().split("")
    var thisLine = document.getElementById(idStr)
    elm.style.display = "block"
    thisLine.innerHTML = ""
    for (let i = 0; i < charArr.length; i++) {
        thisLine.appendChild(colorDivFactory(charArr[i]))
    }
    return true
  }
  /*
  all this does is shift the .innerHTML "down" the DOM ('up' the page),
  leaving the last one blank--giving the illusion of scrolling--if its argument
  is false; true fills the .innerHTML with empty strings & leaves everything blank
  */
  function makeNewLine(recursive) {
    var appendClasses = ["_awayTop","_awayBottom","_relaxed","_basic"]
    var opacityScale = ["0.6","0.8","0.85","inherit"]
    var baseClass = "perspectiveShell"
    var shellElms = []
    var elmsByType = { operators: [], lines: [] }
    var dataByType = { opSymbols: [], lineDivs: [] }
    var elm = {}
    var className = ""
    for (let i = 0; i < appendClasses.length; i++) {
      className = baseClass + appendClasses[i]
      elm = document.getElementsByClassName(className)[0]
      shellElms.push(elm)
      dataByType.opSymbols.push(elm.children[0].children[0].innerHTML)
      dataByType.lineDivs.push(elm.children[0].children[1].innerHTML)
      elmsByType.operators.push(elm.children[0].children[0])
      elmsByType.lines.push(elm.children[0].children[1])
      if (i === appendClasses.length-1) {
        elm.children[0].children[0].id = "currentOperator"
        elm.children[0].children[1].id = "currentLine"
      }
    }
    dataByType.opSymbols.shift()
    dataByType.lineDivs.shift()
    dataByType.opSymbols.push("")
    dataByType.lineDivs.push("")
    if (recursive) {
      dataByType.opSymbols = []
      dataByType.lineDivs = []
      for (let i = 0; i < elmsByType.lines.length; i++) {
        dataByType.opSymbols.push("")
        dataByType.lineDivs.push("")
        //shellElms[i].style.opacity = "0"
      }
    }
    for (let i = 0; i < elmsByType.lines.length; i++ ) {
      shellElms[i].className = baseClass + appendClasses[i]
      /*
      if (elmsByType.lines[i].innerHTML.length) {
        shellElms[i].style.opacity = opacityScale[i]
      }
      */
      elmsByType.operators[i].innerHTML = dataByType.opSymbols[i]
      elmsByType.lines[i].innerHTML = dataByType.lineDivs[i]
    }
  }

  function displayOperator(indexNo) {
    var currentOperator = document.getElementById("currentOperator")
    var displaySymbol = operatorHTML[indexNo]
    var opChar = document.createElement("div")
    opChar.className = "opCharStatic"
    opChar.innerHTML = displaySymbol
    currentOperator.appendChild(opChar)
  }

  function colorCodeNumeric(num) {
    var charArr = num.toString().split("")
    var thisLine = document.getElementById("currentLine")
    thisLine.innerHTML = ""
    for (let i = 0; i < charArr.length; i++) {
        thisLine.appendChild(colorDivFactory(charArr[i]))
    }
  }
  //takes a string (currently a char),
  //color-codes it (currently numerically),
  //and returns it in a div
  function colorDivFactory(val) {
    var numVal = -1
    var colors = [
      "white",
      "whitesmoke",
      "blue",
      "green",
      "gold",
      "firebrick",
      "purple",
      "greenyellow",
      "cornflowerblue",
      "darkorange"
    ]
    var charDiv = document.createElement("div")
    var charStr = ""
    var charNode
    if (isNaN(val)) {
      if (parenths.indexOf(val) != -1 || val == ".") {
        charStr = val != "." ? parenths[parenths.indexOf(val)] : val
        charDiv.style.color = "orchid"
      } else {
        charStr = ops[ops.indexOf(val)]
        charDiv.style.color = "cornflowerblue"
      }
    } else {
      charStr = val
      numVal = Number(val)
      charDiv.style.color = colors[numVal]
    }
    charNode =  document.createTextNode(charStr)
    charDiv.className = "char"
    charDiv.appendChild(charNode)
    return charDiv
  }
  /*
  //
  // Data-processing cocnern
  //
  */
  function mult(subtotal,arg) {
    return subtotal *= arg
  }

  function div(subtotal, arg) {
    return subtotal /= arg
  }

  function rack(subtotal, arg) {
    return subtotal += arg
  }
  //extensible switch for recurring arithetic operations
  function recursiveOps(arr,needsFirstArg,arg,verb) {
    var runningtotal = needsFirstArg? arg : arr[0]
    var startloop = needsFirstArg? 0 : 1
    for (let iv = startloop; iv < arr.length; iv++) {
      switch(verb) {
        case "times":
          runningtotal = mult(runningtotal, arr[iv])
          break
        case "divide" :
          runningtotal = div(runningtotal, arr[iv])
          break
        default :
          runningtotal = rack(runningtotal, arr[iv])
      }
    }
    return runningtotal
  }
  /*
  _calculateResults_
  performs a long list of calculations in order of operations -
  mutliplication and division happen first,
  and their products are substitutied into a long addition problem
  1) multDiv
  2) substitutionOperation
  3) traceOperation
  4) localVars
  5) mainLogic
  */
  function calculateResults() {
    // 1
    function multDivOperation(index) {
      var subvalue = 0
      var truevalue = 0
      switch(trace.firstArg[index]) {
        case "times":
          subvalue = recursiveOps(trace.times[index],false,0,"times")
          if (trace.divide[index].length) {
            truevalue = recursiveOps(trace.divide[index],true,subvalue,"divide")
          } else {
            truevalue = subvalue
          }
          break
        case "divide" :
          subvalue = recursiveOps(trace.divide[index],false,0,"divide")
          if (trace.times[index].length) {
            truevalue = recursiveOps(trace.times[index],true,subvalue,"times")
          } else {
            truevalue = subvalue
          }
          break
        default :
          console.log("get real")
      }
      return truevalue
    }
    // 2
    function substitutionOperation() {
      var newbacktrace = trace.backtrace
      var returnbacktrace = []
      var subVal = 0
      for (let ii = 0; ii < trace.indexIndex.length; ii++) {
        subVal = multDivOperation(ii)
        trace.subVals.push(subVal)
        for (let iii = 0; iii < trace.indexIndex[ii].length; iii++) {
          newbacktrace[trace.indexIndex[ii][iii]] = iii == 0? subVal : null
        }
      }
      for (let v = 0; v < newbacktrace.length; v++) {
        if (newbacktrace[v] != null) {
          returnbacktrace.push(newbacktrace[v])
        }
      }
      return returnbacktrace
    }
    // 3
    function traceOperation(index,isNewStage,isMultDiv) {
      var verb = trace.backtrace[index].slice(1,trace.backtrace[index].length-1)
      if (isNewStage) {
        if (isMultDiv) {
          trace.times.push([])
          trace.divide.push([])
          trace.indexIndex.push([])
          if (!trace.firstArg[trace.times.length-1]) {
            trace.firstArg.push(verb)
          }
          trace[verb][trace[verb].length-1].push(
            trace.backtrace[index-1],
            trace.backtrace[index+1]
          )
          trace.indexIndex[trace.indexIndex.length-1].push(index-1,index,index+1)
        }
      } else if (isMultDiv) {
        trace[verb][trace[verb].length-1].push(trace.backtrace[index+1])
        trace.indexIndex[trace[verb].length-1].push(index,index+1)
      } else {
      }
    }
    /*
    //_calculateResults _M__A__I__N_
    */
    // 4
    var sumArr = []
    var summary = []
    var sum = 0
    // 5
    trace.indexMultDiv = false
    if (trace.backtrace.length == 2 && trace.backtrace[1] == "&equals;") {
      sum = trace.backtrace[0]
    } else {
      if (controller.lineCalc == true) {
        sum = trace.lineCalcSubTotal()
        trace.total = sum
      } else {
        for (let i = 1; i < trace.backtrace.length; i+=2) {
          switch(trace.backtrace[i]) {
            case "&plus;" :
            case "&minus;" :
              if (trace.indexMultDiv == true) {
                trace.indexMultDiv = false
                traceOperation(i,true,trace.indexMultDiv)
              } else {
                traceOperation(i,false,trace.indexMultDiv)
              }
              break
            case "&times;" :
            case "&divide;" :
               if (trace.indexMultDiv == false) {
                 trace.indexMultDiv = true
                 traceOperation(i,true,trace.indexMultDiv)
               } else {
                 traceOperation(i,false,trace.indexMultDiv)
               }
               break;
            default :
          }
        }
        summary = substitutionOperation()
        for (let i = 0; i < summary.length; i+=2) {
          if (i != 0 && summary[i-1] == "&minus;") {
            sumArr.push(summary[i] - (summary[i]*2))
          } else {
            sumArr.push(summary[i])
          }
        }
        sum = recursiveOps(sumArr,false,0,"sum")
        trace.total = sum
      }
    }
    trace.reRack()
    return sum
  }

  function undoLastEntry() {
    return true
  }
  // routes commands, records valid user entries
  function listOperation(numStr,index) {
    var total = 0
    if (trace.backtrace.length != 1) { trace.backtrace.push(Number(numStr)) }
    if (index != 5) { trace.backtrace.push(operatorHTML[index]) }
    switch (index) {
      case 4 :
        total = calculateResults()
        displayResults(total,"resultsText", results)
      break
      case 5 :
        clearClearance = true
        displayControlPanel()
      break
      default :
        controller.opCount += 1
        makeNewLine(false)
        displayOperator(index)
        if (controller.lineCalc) {
          total = trace.backtrace.length >= 4 ?
            trace.lineCalcSubTotal() : trace.backtrace[0]
          trace.total = total
          displayResults(total, "subTotalText", subTotal)
        }
    }
  }
  /*
  _cleanFloatString_
  blocks entry of redundant decimal points and zeros
  1) testForDecimals
  2) testForRedundantZero
  3) localVars
  4) mainLogic
  */
  function cleanFloatString(newString,oldString) {
    // 1
    function testForDecimals(str) {
      if (str.indexOf(".") != -1) {
        return true
      }
      return false
    }
    // 2
    function testForRedundantZero(str) {
      if (str[0] === "0" && str.length === 1) {
        return true
      }
      return false
    }
    // 3
    var cleanString = newString
    var result = false
    // 4
    if (oldString) {
      if (newString === ".") {
        result = testForDecimals(oldString)
      } else if (newString === "0" ) {
        result = testForRedundantZero(oldString)
      }
    } else {
      if (newString === ".") { cleanString = "0."}
    }
    if (result) { cleanString = "" }
    return cleanString
  }

  // translates user input into calculator commands
  function initTenKey(signal, bool) {
    textBox.style.display = "block"
    var validVal = ""
    if ((Number(signal) || signal == "." || signal == "0")) {
      if (!controller.operatorOnly) {
        if (controller.history[controller.opCount].length === 1) {
          validVal = cleanFloatString(signal,"")
          controller.history[controller.opCount].push(validVal)
        } else {
          validVal = cleanFloatString(signal,controller.history[controller.opCount][1])
          controller.history[controller.opCount][1] += validVal
        }
        colorCodeNumeric(controller.history[controller.opCount][1])
      }
    } else {
      //if there are data in the current line, all operators will work
      if (controller.history[controller.opCount][1]) {
        if (signal != "equals" && signal != "laquo" ) {
          controller.history.push([])
          controller.history[controller.opCount + 1].push(signal)
        }
        listOperation(
          controller.history[controller.opCount][1],
          operatorHTML.indexOf("&" + signal + ";")
        )
        controller.operatorOnly = false
      } else {
        if (signal === "laquo" ) {
          displayControlPanel()
        } else {
        }
      }
    }
  }
  // rests data entry
  function clearOperation(modeArg) {
    var traceKeys = Object.keys(trace)
    makeNewLine(true)
    clearClearance =  "false"
    textBox.style.display = "none"
    results.style.display = "none"
    document.getElementById("resultsText").innerHTML = ""
    document.getElementById("subTotalText").innerHTML = ""
    debug.innerHTML = ""
    controller = { lineCalc : modeArg, base: 10, operatorOnly: false,
                   history: [[null]],  opCount: 0 }
    for (let i = 0; i < traceKeys.length; i++) {
      if (traceKeys[i] != "lineCalcSubTotal" && traceKeys[i] != "reRack") {
        trace[traceKeys[i]] =
          (traceKeys === "total" || traceKeys === "reRacks")?
            0 : []
      }
    }
  }

  /*
  //
  //_M__A__I__N_
  //
  application
  1) References
  2) DOM
  3) controller
  */
  // 0
  var debug = document.getElementById("debug")
  // 1
  // References
  var parenths = ["(",")"]
  var ops = ["+","-","*","/","="]
  var operatorHTML = [
    "&plus;","&minus;","&times;","&divide;","&equals;","&laquo;"
    ]
  var operatorCodes = [107,109,106,111,13]
  var tenKeyCodes = [96,97,98,99,100,101,102,103,104,105,110]
  var numKeyCodes = [48,89,50,51,52,53,54,55,56,57]
  var shift = 16
  var boolArr = [false, true]
  var displayArr = ["none","block"]
  // 2
  //DOM / View - concern
  var app = document.getElementById("app")
  // Building the DOM
  var keys = findKeys()
  var output = document.getElementById("theOutput")
  var textBox = document.getElementById("textBox")
  var results = document.getElementById("resultsBox")
  var subTotal = document.getElementById("subTotalBox")
  var controlPanel = document.getElementById("controlPanelBox")
  var controlPanelConsole = document.getElementById("controlPanelText")
  var modals = document.getElementsByClassName("modal")
  var closeModals = document.getElementsByClassName("closeModal")
  var radios = document.getElementsByClassName("modeButton")
  var clearButton = document.getElementsByClassName("clearButton")[0]
  var startButton = document.getElementsByClassName("clearButtonSmall_noLink")[0]
  var nav = document.getElementsByClassName("subModal")[0]
  var openNav = document.getElementsByClassName("openSubModal")[0]
  var closeNav = document.getElementsByClassName("closeSubModal")[0]
  // 3
  // Data-processing cocnern
  var trace = { backtrace: [], plus: [], minus: [], times: [], divide: [],
            total: 0, indexIndex: [], firstArg: [], subVals: [], reRacks: 0 }
  var controller = {}
  var clearClearance = false
  //populates controller poperties
  clearOperation()

  trace.lineCalcSubTotal = function () {
    var args = [trace.total]
    var opStr = trace.backtrace[trace.backtrace.length - 3].slice(
      1,
      trace.backtrace[trace.backtrace.length - 3].length-1
    )
    if (opStr == "minus") {
      args.push(
        trace.backtrace[trace.backtrace.length - 2] -
        (trace.backtrace[trace.backtrace.length - 2] * 2)
      )
    } else {
      args.push(trace.backtrace[trace.backtrace.length - 2])
    }
    trace.total = recursiveOps(args,false,0,opStr)
    return trace.total
  }

  trace.reRack = function () {
    var tracekeys = Object.keys(trace)
    var protectedKeys = ["total","reRacks","lineCalcSubTotal","reRack"]
    for (let i = 0; i < tracekeys.length; i++) {
      if (protectedKeys.indexOf(tracekeys[i]) === -1)
        trace[tracekeys[i]] = []
    }
    trace.backtrace.push(trace.total)
    trace.reRacks += 1
  }

  openNav.addEventListener("click", function () {
    nav.style.display = "block"
    controlPanelConsole.style.opacity = "0.35"
    this.style.display = "none"
  })

  closeNav.addEventListener("click", function () {
    openNav.style.display = "block"
    controlPanelConsole.style.opacity = "1"
    nav.style.display = "none"
  })


  clearButton.addEventListener("click", function () {
    if (clearClearance) {
      clearOperation(controller.lineCalc)
    }
  })

  for (let i = 0; i < radios.length; i++) {
    radios[i].onchange = function () {
      controller.lineCalc = boolArr[i]
      subTotal.style.display = displayArr[i]
    }
  }

  startButton.addEventListener("click", function () {
    controlPanel.style.display = "none"
  })

  for (let i = 0; i < closeModals.length; i++) {
    if (modals[i]) {
      clickToClose(closeModals[i],modals[i])
    }
  }
  window.addEventListener("keydown", function () {
    textBox.style.disply = "block"
  })

  window.addEventListener("keydown", function () {
    var opIndex = operatorCodes.indexOf(event.keyCode)
    var numIndex = tenKeyCodes.indexOf(event.keyCode)
    var symbol = ""
    // waits for operator input from laptop/desktop 10-key
    if (opIndex != -1 ) {
      symbol = operatorHTML[opIndex].slice(1,operatorHTML[opIndex].length -1)
      initTenKey(symbol, true)
    // waits for numeric or decimal point input from laptop/desktop 10-key
    } else if (numIndex != -1) {
      symbol = (numIndex === 10)? "." : numIndex.toString()
      initTenKey(symbol, true)
    } else {
    }
  })
}

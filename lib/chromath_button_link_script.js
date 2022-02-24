'use strict'

var links = document.getElementsByClassName("clearButtonLink")
var buttons = document.getElementsByClassName("clearButtonSmall")

for (let i = 0; i < buttons.length; i++) {
  assignClick(buttons[i],links[i])
}

function assignClick(element,link) {
  var url = link.getAttribute("href")
  element.addEventListener("click", function () {
    window.location.assign(url)
  })
}

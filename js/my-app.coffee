
myApp = new Framework7
  animateNavBackIcon: true

$$ = Dom7

mainView = myApp.addView '.view-main', {
  dynamicNavbar: true
  domCache: true
}

window.start_list = new Classes.QuestionList
  filename: "datasets.json"
  list_container: $('#question-list-block')
  list_template: _.template($('.left-panel').first().html())
  containers_container: $('#pages')
  containers_template: _.template($('.question-page').first().html())
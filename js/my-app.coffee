
myApp = new Framework7
  animateNavBackIcon: true

$$ = Dom7

mainView = myApp.addView '.view-main', {
  dynamicNavbar: true
  domCache: true
}

window.questions_all = new Classes.Questions
  dataset: "questions.json"
  container: $('#questions-all')
  template: _.template($('.question').first().html())

window.questions_midterm = new Classes.Questions
  dataset: "questions_midterm.json"
  container: $('#questions-midterm')
  template: _.template($('.question').first().html())

myApp = new Framework7
  animateNavBackIcon: true

$$ = Dom7

mainView = myApp.addView '.view-main', {
  dynamicNavbar: true
  domCache: true
}

window.questions = new Classes.Questions
  container: $('#questions')
  template: _.template($('.question').first().html())
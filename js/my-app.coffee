
myApp = new Framework7
  animateNavBackIcon: true

$$ = Dom7

mainView = myApp.addView '.view-main', {
  dynamicNavbar: true
  domCache: true
}

$(document).ready ->
  $('.source-link').click ->
    window.location.href = "http://www.github.com/tulak/pv080-drill/"

window.questions_all = new Classes.Questions
  dataset: "questions.json"
  container: $('#questions-all')
  template: _.template($('.question').first().html())

window.questions_midterm = new Classes.Questions
  dataset: "questions_midterm.json"
  container: $('#questions-midterm')
  template: _.template($('.question').first().html())
  
window.questions_PV119 = new Classes.Questions
  dataset: "questions_PV119.json"
  container: $('#questions-PV119')
  template: _.template($('.question').first().html())
  
window.questions_PB151 = new Classes.Questions
  dataset: "questions_PB151.json"
  container: $('#questions-PB151')
  template: _.template($('.question').first().html())
  
window.questions_PV157_all = new Classes.Questions
  dataset: "questions_PV157.json"
  container: $('#questions-PV157-all')
  template: _.template($('.question').first().html())

window.questions_PV157_midterm = new Classes.Questions
  dataset: "questions_PV157_midterm.json"
  container: $('#questions-PV157-midterm')
  template: _.template($('.question').first().html())
  
window.questions_IB101 = new Classes.Questions
  dataset: "questions_IB101.json"
  container: $('#questions-IB101')
  template: _.template($('.question').first().html())

window.questions_PB071 = new Classes.Questions
  dataset: "questions_PB071.json"
  container: $('#questions-PB071')
  template: _.template($('.question').first().html())

window.questions_Bi4020 = new Classes.Questions
  dataset: "questions_Bi4020.json"
  container: $('#questions-Bi4020')
  template: _.template($('.question').first().html())
  

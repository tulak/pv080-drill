// Initialize your app
var myApp = new Framework7({
    animateNavBackIcon:true
});

// Export selectors engine
var $$ = Dom7;

// Add main View
var mainView = myApp.addView('.view-main', {
    // Enable dynamic Navbar
    dynamicNavbar: true,
    // Enable Dom Cache so we can use all inline pages
    domCache: true
});

window.questions = new Classes.Questions({
  container: $('#questions'),
  template: _.template($('.question').first().html())
});

//var question_template = _.template($('.question').first().html());
//var first_question = question_template(
//  {
//    "name": "Pseudonymita (podle Společných kritérií) zajišťuje možnost použití zdrojů nebo služeb systému tak, že:",
//    "answers": [
//      {
//        "body": "identita uživatele zůstane trvale skryta",
//        "right": false
//      },
//      {
//        "body": "identita uživatele zůstane skryta specifickým uživatelům, pro specifické operace",
//        "right": false
//      },
//      {
//        "body": "identita uživatele zůstane skryta, ale specifikované entity za nespecifikovaných podmínek mohou tento stav zvrátit",
//        "right": false
//      },
//      {
//        "body": "identita uživatele zůstane skryta, ale to platí pouze pro původně neveřejné pseudonymy",
//        "right": false
//      },
//      {
//        "body": "identita uživatele zůstane skryta, ale specifikované entity za specifikovaných podmínek mohou tento stav zvrátit",
//        "right": true
//      },
//      {
//        "body": "identita uživatele zůstane skryta ostatním uživatelům, ale ne administrátorům",
//        "right": false
//      }
//    ]
//  }
//);
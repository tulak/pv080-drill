(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Classes.Questions = (function() {
    function Questions(options) {
      this.nextQuestion = __bind(this.nextQuestion, this);
      this.submit = __bind(this.submit, this);
      this.popRandomQuestion = __bind(this.popRandomQuestion, this);
      this.renderQuestion = __bind(this.renderQuestion, this);
      this.container = options.container;
      this.template = options.template;
      $('#submit-button').click(this.submit);
      $('#cancel-button').click(this.cancel);
      this.done_questions = [];
      $.getJSON("questions.json", (function(_this) {
        return function(data) {
          _this.questions = data;
          return _this.renderQuestion(_this.popRandomQuestion());
        };
      })(this));
    }

    Questions.prototype.renderQuestion = function(question) {
      var html;
      if (this.current_question) {
        this.done_questions << this.current_question;
      }
      this.current_question = question;
      html = this.template(question);
      return this.container.html(html);
    };

    Questions.prototype.popRandomQuestion = function() {
      var random_index;
      if (this.questions.length === 0) {
        this.questions = this.done_questions;
        this.done_questions = [];
      }
      random_index = _.random(0, this.questions.length - 1);
      return this.questions[random_index];
    };

    Questions.prototype.submit = function() {
      return _.each($('input[name=answer]'), function(answer) {
        var li;
        li = $(answer).parent().parent();
        li.removeClass("color-red");
        if (answer.checked && $(answer).attr('value') === "false") {
          li.addClass("color-red");
        }
        if (!answer.checked && $(answer).attr('value') === "true") {
          return li.addClass("color-red");
        }
      });
    };

    Questions.prototype.nextQuestion = function() {
      return this.renderQuestion(this.popRandomQuestion());
    };

    return Questions;

  })();

}).call(this);

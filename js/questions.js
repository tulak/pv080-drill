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
      this.container.html(html);
      $('#submit-button').click(this.submit);
      return $('#next-button').click(this.nextQuestion);
    };

    Questions.prototype.popRandomQuestion = function() {
      var random_index, ret;
      if (this.questions.length === 0) {
        this.questions = this.done_questions;
        this.done_questions = [];
      }
      random_index = _.random(0, this.questions.length - 1);
      ret = this.questions[random_index];
      this.questions[random_index] = void 0;
      this.questions = _.compact(this.questions);
      this.done_questions.push(ret);
      return ret;
    };

    Questions.prototype.submit = function() {
      return _.each($('input[name=answer]'), function(answer) {
        var li;
        li = $(answer).parent().parent();
        li.removeClass("bad");
        li.removeClass("good");
        li.removeClass("missing");
        if (answer.checked && $(answer).attr('value') === "false") {
          li.addClass("bad");
        }
        if (!answer.checked && $(answer).attr('value') === "true") {
          li.addClass("missing");
        }
        if (answer.checked && $(answer).attr('value') === "true") {
          return li.addClass("good");
        }
      });
    };

    Questions.prototype.nextQuestion = function() {
      return this.renderQuestion(this.popRandomQuestion());
    };

    return Questions;

  })();

}).call(this);

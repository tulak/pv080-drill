class Classes.Questions
  constructor: (options)->
    @container = options.container
    @template = options.template

    @done_questions = []
    $.getJSON "questions.json", (data)=>
      @questions = data
      @renderQuestion(@popRandomQuestion())

  renderQuestion: (question)=>
    @done_questions << @current_question if @current_question
    @current_question = question
    html = @template {
      question: question
      question_index: @done_questions.length
      total_questions: @done_questions.length + @questions.length
    }
    @container.html(html)
    $('#submit-button').click @submit
    $('#next-button').click @nextQuestion
    $('#source-link').on "click", ->
      window.location.href = "http://www.github.com/tulak/pv080-drill/"

  popRandomQuestion: =>
    if @questions.length == 0
      @questions = @done_questions
      @done_questions = []
    random_index = _.random(0,@questions.length-1)
    ret = @questions[random_index]
    @questions[random_index] = undefined
    @questions = _.compact(@questions)
    @done_questions.push ret
    ret

  submit: =>
    _.each $('input[name=answer]'), (answer)->
      li = $(answer).parent().parent()
      li.removeClass "bad"
      li.removeClass "good"
      li.removeClass "missing"
      if answer.checked and $(answer).attr('value') == "false"
       li.addClass "bad"
      if !answer.checked and $(answer).attr('value') == "true"
        li.addClass "missing"
      if answer.checked and $(answer).attr('value') == "true"
        li.addClass "good"

  nextQuestion: =>
    @renderQuestion(@popRandomQuestion())
class Classes.Questions
  constructor: (options)->
    @container = options.container
    @template = options.template
    $('#submit-button').click @submit
    $('#cancel-button').click @cancel

    @done_questions = []
    $.getJSON "questions.json", (data)=>
      @questions = data
      @renderQuestion(@popRandomQuestion())

  renderQuestion: (question)=>
    @done_questions << @current_question if @current_question
    @current_question = question
    html = @template(question)
    @container.html(html)

  popRandomQuestion: =>
    if @questions.length == 0
      @questions = @done_questions
      @done_questions = []
    random_index = _.random(0,@questions.length-1)
    @questions[random_index]

  submit: =>
    _.each $('input[name=answer]'), (answer)->
      li = $(answer).parent().parent()
      li.removeClass "color-red"
      if answer.checked and $(answer).attr('value') == "false"
       li.addClass "color-red"
      if !answer.checked and $(answer).attr('value') == "true"
        li.addClass "color-red"

  nextQuestion: =>
    @renderQuestion(@popRandomQuestion())
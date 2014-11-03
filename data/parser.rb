require 'pry'
require 'active_support/all'

class Parser
  QUESTION_SEPARATOR = /====/
  ANSWER_SIGN = /-/
  RIGHT_ANSWER_SIGN = /\*/

  attr_reader :questions, :errors
  def initialize file
    @file = File.open(file, "r")
  end

  def parse
    data = @file.read
    questions = data.split(QUESTION_SEPARATOR)
    @questions = questions.collect do |question|
      parse_question question
    end
  end

  def parse_question question_data
    lines = question_data.split(/\n/)
    question = Question.new(nil, [])
    lines.each do |line|
      next if line.empty?
      if line =~ /^#{ANSWER_SIGN}(#{RIGHT_ANSWER_SIGN}?)(.*)/
        question.answers << Answer.new($2.strip, !$1.empty?)
      else
        if question.name
          raise DuplicitQuestionNameError.new(question_data, question.name, line)
        else
          question.name = line
        end
      end
    end
    question
  rescue DuplicitQuestionNameError => e
    @errors ||= []
    @errors << e
    question
  end

  def as_json
    questions.collect(&:as_json)
  end

  def duplicites
    @questions.group_by{|q| q.name }.find_all{|g,qs| qs.size > 1}
  end
end

class DuplicitQuestionNameError < StandardError
  attr_reader :question_data, :first_name, :second_name
  def initialize question_data, first_name, second_name
    @question_data = question_data
    @first_name = first_name
    @second_name = second_name
  end
end

class Answer < Struct.new(:body, :right)
  def to_s
    "#{right ? "*" : "-"} #{body} "
  end

  def as_json
    {
        body: body,
        right: right
    }
  end
end

class Question < Struct.new(:name, :answers)
  def to_s
    name.tap do |r|
      answers.each do |a|
        r << "\n #{a}"
      end
    end
  end

  def as_json
    {
        name: name,
        answers: answers.collect(&:as_json)
    }
  end
end

p = Parser.new "data.txt"
# p.parse

binding.pry
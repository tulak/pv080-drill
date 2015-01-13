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

# p1 = Parser.new "data.txt"
# p1.parse
# f1 = File.open("../questions.json", "w")
# f1.write p1.questions.as_json.to_json
# f1.close

#p2 = Parser.new "data-vnitro.txt"
#p2.parse
#f2 = File.open("../questions_midterm.json", "w")
#f2.write p2.questions.as_json.to_json
#f2.close

p3 = Parser.new "data-PV119.txt"
p3.parse
f3 = File.open("../questions_PV119.json", "w")
f3.write p3.questions.as_json.to_json
f3.close

#p4 = Parser.new "data-PB151.txt"
#p4.parse
#f4 = File.open("../questions_PB151.json", "w")
#f4.write p4.questions.as_json.to_json
#f4.close

binding.pry
require 'coffee-script'
require 'pry'

js = CoffeeScript.compile File.read("js/questions.coffee")
f = File.open("js/questions.js", "w")
f.write js
f.close
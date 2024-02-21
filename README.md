# nGram-LM

This is an easy and educational implementation of a language model in PHP, specifically based on a 
[Markov Chain](https://en.wikipedia.org/wiki/Markov_chain). The original code this repository was created from can be
found in https://github.com/AcidBurn86/LM-nGram-with-php.

In simple terms, a Markov Chain involves predicting the next step in a sequence based only on the current step, 
without taking into account the history of previous steps. In the case of a language model, this means predicting 
the next word based on the preceding words.

It's a simple but good example for understanding the basics of Natural Language Processing (NLP) and how statistical
language models work. 

# How to use
First extend memory_limit in your php.ini to 10240M (10gb) aprox.
and also max_execution_time to 300 seconds.

In the first run if there is no "trained" file in the directory it will train the model, it could take some time,
depending on your hardware.

You can adjust the value of the **$ngrams** variable prior to running the model. 

This value determines the size of the word sequences (n-grams) the model uses for training and prediction, 
based on the preceding word. Please note that the number of possibilities grows exponentially with the value of n-grams, 
so caution is advised when setting this variable. 

A value of 6 is usually sufficient, while a higher value, like 15, might cause the program to freeze.

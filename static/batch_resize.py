# Subtract 1 from the index in the image filename.
import sys
import os
import string
import re 
from subprocess import call

def sorted_nicely(l): 
    convert = lambda text: int(text) if text.isdigit() else text 
    alphanum_key = lambda key: [ convert(c) for c in re.split('([0-9]+)', key) ] 
    return sorted(l, key = alphanum_key)

category = sys.argv[1]
width = sys.argv[2]
height= sys.argv[3]

for filename in sorted_nicely(os.listdir(category)):
    if not filename == '.DS_Store':
        command = "convert " + category + '/' + filename + " -resize " + width + 'x' + height + " " + category + '/' + filename
        print command
        call(command, shell=True)
#!/bin/bash
#
# this script should not be run directly,
# instead you need to source it from your .bashrc,
# by adding this line:
#   . ~/bin/myprog.sh
#

function create_folders() {
  cd scripts
  mkdir ../temp
  mkdir ../temp-pic
  mkdir ../temp-pic-big
  mkdir ../photos
}

create_folders

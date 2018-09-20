#!/bin/bash

# Resize all PNG files in this dir to given size
# Requires imagemagick convert
size=$1
if [ "$1" = "" ]; then
	size=48
fi
quality=100
for file in ./*.png; do
	echo Resizing $file to ${size}px 
	convert $file -thumbnail $size -quality $quality $file
done
chmod 644 *.png



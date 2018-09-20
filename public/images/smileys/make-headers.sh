#!/bin/bash

# Convert all large icons to headers
# Requires imagemagick convert
size=18
quality=100
rm -rf icon_*_h.png
for file in ./icon_*.png; do
	tmp="${file##*_}" #removes icon_, keeps extension
	name="${tmp%.[^.]*}"
	echo Converting $file to icon_${name}_h.png at ${size}px 
	convert $file -thumbnail $size -quality $quality icon_${name}_h.png
done
chmod 644 icon_*.png



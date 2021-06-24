# Slideshow module #

This fork implements comments, reading position saving and video-adding. Developed as part of coursework for an
Open-Source Software course.

## Authors ##
  * james@velomatic.com (https://github.com/noparadise)
  * alan.whittamore@castlecollege.ac.uk
  * Paul Vaughan (https://github.com/vaughany)
  * Los tres mosqueteros for an Open-Source Software class

## Installation ##
Clone the repository, rename the folder from `moodle-mod_slideshow` to `slideshow`. Place it in `moodle/mod/`
and navigate to your moodle site. It should prompt you to install the plugin.

## Usage ##
### Slideshow creation ###
On your computer:
  * Export a presentation to images (for example OpenOffice's export to HTML).
  * Create a zip file of the folder with images.

In Moodle:
  * Add a "slideshow" activity.
  * Upload the zip file and unzip it.

### Slideshow usage ###
Click on a thumbnail to navigate, click on the image to progress. The reading position is kept for each slideshow.
One YouTube or Vimeo video can be added to each slide and positioned by dragging and dropping. Comments can be added
to each slideshow.

Users with teacher role and upwards can choose
  * Whether to display captions, titles and edit them
  * Position of thumbnails and captions relative to main image
  * Options for auto-progress of slides in new window
  * Slide maximum dimensions (x and y pixels)
  * Whether to include some javascript to disable right-clicking and remove the "image in new window" link

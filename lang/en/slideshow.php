<?PHP // $Id$ 

//
$string['modulename'] = 'Slideshow';
$string['pluginname'] = 'Slideshow';
$string['modulenameplural'] = 'Slideshows';
$string['pluginadministration'] = 'Slideshow';
$string['slideshowsfound'] = 'Slideshows in course {$a}';

// for mod.html
$string['chooseapacket'] = 'Choose or change dir';
$string['coursepacket'] = 'Slideshow directory';
$string['maindirectory'] = 'Main Files Directory';
$string['no_GD_no_thumbs'] = 'The GD extension for PHP is not installed, so no thumbnails will be created.';
$string['display_filename'] = 'Display filename/caption?';
$string['display_none'] = 'Nowhere';
$string['display_over'] = 'Above image';
$string['display_under'] = 'Below image';
$string['display_right'] = 'Right of image';
$string['thumbnail_layout'] = 'Thumbnail position:';
$string['onblack'] = 'Slideshow on black?';
$string['centred'] = 'Centred?';
$string['showtime'] = 'Slideshow popup delay time:';
$string['noautoplay'] = 'No autoplay';
$string['htmlcaptions'] = 'HTML editor for captions?';
$string['comments'] = 'Allow comments?';

// for view.php
$string['none_found'] = 'No images found in main files directory';
$string['dir_problem'] = 'Unspecified problem with directory specified';
$string['open_new'] = 'Open in new window to print';
$string['warning'] = 'WARNING!';
$string['files_too_big'] = 'The file(s) listed below are larger than the site threshold. An attempt has been made at resizing them to less than ';
$string['recompress'] = 'Force recompress files ';
$string['edit_captions'] = 'Edit captions';
$string['original_exists'] = 'Original already exists in ';
$string['original_moved'] = 'Original moved to ';
$string['original_deleted'] = 'Original deleted.';
// for captions.php
$string['captiontext'] = 'You can enter quite a large amount of text and html in the caption fields. <p>To make your captions permanent, you must use the &quot;Save&quot; button which is near the bottom of the page.';
$string['captionedit'] = 'Editing captions';
$string['save_changes'] = 'Save changes';
$string['saved'] = 'Your captions have been saved.';
$string['continue'] = 'Continue';
$string['autopopup'] = 'Autoplay in popup';
$string['caption'] = 'Caption';
$string['title'] = 'Title';
$string['noauth'] = 'You do not have permission to edit captions.';

// for comments
$string['comment'] = 'Comment';
$string['comment_insert_error'] = 'There was an error inserting the comment into the database.';
$string['comment_add'] = 'Add a comment to this slide';
$string['comment_instructions'] = 'Comments are public and viewable by all.';
$string['comments_header'] = 'Comments';
$string['comments_not_allowed'] = 'Comments are disabled for this slideshow.';

// for media
$string['media'] = 'Media';
$string['media_add'] = 'Add/edit media for this slide';
$string['media_instructions'] = 'Paste the URL to a Youtube or Vimeo video in the URL box. You can specify height and width and also drag the video box for position.';
$string['media_header'] = 'Media';
$string['media_insert_error'] = 'There was an error inserting the media into the database.';
$string['media_update_error'] = 'There was an error updating the media information into the database.';
$string['media_edit_url'] = 'URL';
$string['media_edit_x'] = 'X position';
$string['media_edit_y'] = 'Y position';
$string['media_edit_width'] = 'Width';
$string['media_edit_height'] = 'Height';
$string['media_edit_position'] = 'Drag this box to the desired position for your media';
 
// for config.html
$string['configmaxbytes'] = 'Largest filesize permissible (Kb) before the image is resized and saved';
$string['configmaxwidth'] = 'Maximum width for images (pixels)';
$string['configmaxheight'] = 'Maximum height for images (pixels)';
$string['keeporiginals'] = 'Keep originals?<br />If yes, the original image will be shown for printing. If no, only the resized version of the image is kept and server space is maximised.';
$string['securepix'] = 'This will include javascript to prevent easy saving of the image and remove the direct link.';

$string['modulename_help'] = 'The slideshow module enables teachers to upload images/videos and organize them into a slideshow with comments.
<h3>Usage</h3>
<strong>Slideshow creation</strong>

<p>On your computer:
<ul>
<li>Export a presentation to images (for example OpenOffice\'s export to HTML).</li>
<li>Create a zip file of the folder with images.</li>
</p>
<p>In Moodle:
Add a "slideshow" activity.
Upload the zip file and unzip it.
</p>
<strong>Slideshow usage</strong>
<p>Click on a thumbnail to navigate, click on the image to progress. The reading
position is kept for each slideshow. One YouTube or Vimeo video can be added to
each slide and positioned by dragging and dropping. Comments can be added to
each slideshow.
</p>
<p>
Users with teacher role and upwards can choose
<ul>
<li>Whether to display captions, titles and edit them</li>
<li>Position of thumbnails and captions relative to main image</li>
<li>Options for auto-progress of slides in new window</li>
<li>Slide maximum dimensions (x and y pixels)</li>
<li>Whether to include some javascript to disable right-clicking and remove the "image in new window" link</li>
</p>';
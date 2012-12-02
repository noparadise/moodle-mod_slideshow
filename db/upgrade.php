<?php

function xmldb_slideshow_upgrade($oldversion=0) {
	global $DB;
	$dbman = $DB->get_manager();
 
/// This function does anything necessary to upgrade 
/// older versions to match current functionality  

    /*if ($oldversion < 0) {*/

        //// Define table intelligent_slideshow_media to be created
        //$table = new xmldb_table('intelligent_slideshow_media');

        //// Adding fields to table intelligent_slideshow_media
        //$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        //$table->add_field('slideshowid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        //$table->add_field('slidenumber', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);
        //$table->add_field('fileid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        //// Adding keys to table intelligent_slideshow_media
        //$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        //$table->add_key('slideshowid', XMLDB_KEY_FOREIGN, array('slideshowid'), 'intelligent_slideshow', array('id'));

        //// Conditionally launch create table for intelligent_slideshow_media
        //if (!$dbman->table_exists($table)) {
            //$dbman->create_table($table);
        //}

        //// intelligent_slideshow savepoint reached
        //upgrade_mod_savepoint(true, XXXXXXXXXX, 'intelligent_slideshow');
    /*}*/

   /* if ($oldversion < 0) {*/

        //// Define table intelligent_slideshow_read_p to be created
        //$table = new xmldb_table('intelligent_slideshow_read_p');

        //// Adding fields to table intelligent_slideshow_read_p
        //$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        //$table->add_field('slideshowid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        //$table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        //$table->add_field('slidenumber', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);

        //// Adding keys to table intelligent_slideshow_read_p
        //$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        //$table->add_key('slideshowid', XMLDB_KEY_FOREIGN, array('slideshowid'), 'intelligent_slideshow', array('id'));

        //// Adding indexes to table intelligent_slideshow_read_p
        //$table->add_index('userid', XMLDB_INDEX_NOTUNIQUE, array('userid'));

        //// Conditionally launch create table for intelligent_slideshow_read_p
        //if (!$dbman->table_exists($table)) {
            //$dbman->create_table($table);
        //}

        //// intelligent_slideshow savepoint reached
        //upgrade_mod_savepoint(true, XXXXXXXXXX, 'intelligent_slideshow');
    /*}*/

    if ($oldversion < 2012120200) {

        // Define table slideshow_comments to be created
        $table = new xmldb_table('slideshow_comments');

        // Adding fields to table slideshow_comments
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('slideshowid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('slidenumber', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);
        $table->add_field('slidecomment', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table slideshow_comments
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('slideshowid', XMLDB_KEY_FOREIGN, array('slideshowid'), 'slideshow', array('id'));

        // Adding indexes to table slideshow_comments
        $table->add_index('userid', XMLDB_INDEX_NOTUNIQUE, array('userid'));

        // Conditionally launch create table for slideshow_comments
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // slideshow savepoint reached
        upgrade_mod_savepoint(true, 2012120200, 'slideshow');
    }
    return true;
}

?>

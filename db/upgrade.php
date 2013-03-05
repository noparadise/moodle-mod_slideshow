<?php
require_once($CFG->dirroot.'/mod/slideshow/db/upgradelib.php');
function xmldb_slideshow_upgrade($oldversion=0) {
	global $DB;
	$dbman = $DB->get_manager();
 
/// This function does anything necessary to upgrade 
/// older versions to match current functionality  


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

    if ($oldversion < 2012120300) {

        // Define table slideshow_read_positions to be created
        $table = new xmldb_table('slideshow_read_positions');

        // Adding fields to table slideshow_read_positions
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('slideshowid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('slidenumber', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table slideshow_read_positions
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('slideshowid', XMLDB_KEY_FOREIGN, array('slideshowid'), 'slideshow', array('id'));

        // Adding indexes to table slideshow_read_positions
        $table->add_index('userid', XMLDB_INDEX_NOTUNIQUE, array('userid'));

        // Conditionally launch create table for slideshow_read_positions
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // slideshow savepoint reached
        upgrade_mod_savepoint(true, 2012120300, 'slideshow');
    }

		if ($oldversion < 2012122800) {

				// Define table slideshow_media to be created
				$table = new xmldb_table('slideshow_media');

				// Adding fields to table slideshow_media
				$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
				$table->add_field('slideshowid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
				$table->add_field('slidenumber', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, null);
				$table->add_field('url', XMLDB_TYPE_CHAR, '1333', null, null, null, null);
        $table->add_field('width', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('height', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('x', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('y', XMLDB_TYPE_INTEGER, '4', null, null, null, null);

				// Adding keys to table slideshow_media
				$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
				$table->add_key('slideshowid', XMLDB_KEY_FOREIGN, array('slideshowid'), 'slideshow', array('id'));

				// Conditionally launch create table for slideshow_media
				if (!$dbman->table_exists($table)) {
						$dbman->create_table($table);
				}

				// slideshow savepoint reached
				upgrade_mod_savepoint(true, 2012122800, 'slideshow');
		}

    if ($oldversion < 2012122900) {

        // Define field commentsallowed to be added to slideshow
        $table = new xmldb_table('slideshow');
        $field = new xmldb_field('commentsallowed', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '1', 'htmlcaptions');

        // Conditionally launch add field commentsallowed
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // slideshow savepoint reached
        upgrade_mod_savepoint(true, 2012122900, 'slideshow');
    }
    if($oldversion<2012122900){
    	//migration of course files
    	slideshow_migrate_20();
    	
    }

    return true;
}
?>

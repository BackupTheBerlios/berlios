<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.1 2004/03/16 14:51:57 helix Exp $

require('pre.php');
require('../feature/feature_utils.php');
require('../feature/feature_data.php');

if ($group_id) {
	$project=&project_get_object($group_id);

	switch ($func) {

		case 'addfeature' : {
			include '../feature/add_feature.php';
			break;
		}
		case 'postaddfeature' : {
			/*

				Create a new feature request

			*/
			if (feature_data_create_feature($project,$feature_category_id,$user_email,$summary,$details)) {
				$feedback = 'Successfully Created Feature Request';
				include '../feature/browse_feature.php';
			} else {
				//some kind of error in creation
				exit_error('ERROR',$feedback);
			}
			break;
		}
		case 'postmodfeature' : {
			/*
				Modify a feature request

				Used by feature admins
			*/
			if (feature_data_update ($project,$feature_id,$priority,$feature_status_id,
				$feature_category_id,$assigned_to,$summary,$canned_response,$details)) {
				$feedback = 'Feature Ticket(s) Updated';
				include '../feature/browse_feature.php';
			} else {
				//some kind of error in creation
				exit_error('ERROR',$feedback);
			}
			break;
		}
		case 'postaddcomment' : {
			/*
				Attach a comment to a feature request

				Used by non-admins
			*/
			if (feature_data_add_comment ($project,$feature_id,$details,$user_email)) {
				$feedback='Comment Added To Feature Ticket';
				include '../feature/browse_feature.php';
			} else {
				//some kind of error in creation
				exit_error('ERROR',$feedback);
			}
			break;
		}
		case 'browse' : {
			include '../feature/browse_feature.php';
			break;
		}
		case 'detailfeature' : {
			if ($project->userIsFeatureAdmin()) {
				include '../feature/mod_feature.php';
			} else {
				include '../feature/detail_feature.php';
			}
			break;
		}
		default : {
			include '../feature/browse_feature.php';
			break;
		}
	}

} else {

	exit_no_group();

}

?>

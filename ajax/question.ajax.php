<?php
require_once '../config/config.php';
require_once DIR_ROOT.'/classes/question.class.php';

define('FULL_LIKE', true);// do like as %search_term% not search_term%

$action = $_REQUEST['action'];
$orderby = (isset($_REQUEST['orderby']))? $_REQUEST['orderby'] : '';
$dir =(isset($_REQUEST['dir']))? $_REQUEST['dir'] : '';
$year =(!empty($_REQUEST['year']))? $_REQUEST['year'] : DEFAULT_YEAR;

$question = new question($year);


$filter=$question->table->buildWhereArrayFromRequest();

switch($action){
	case 'list':$page="staff.php?action=show" ;
				$filter=$question->table->buildWhereArrayFromRequest();
				$question->getQuestionList('AJAX', $orderby, $dir, $filter); 
			break;
	case 'show-question' : 
				$id=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
				$pid=(isset($_REQUEST['pid']))? $_REQUEST['pid'] : '';
				echo $question->getQuestionDetailsbyAdvertismentId($id, $pid);
			break;
	case 'edit-question' : 
				$id=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
				$pid=(isset($_REQUEST['pid']))? $_REQUEST['pid'] : '';
				echo $question->getQuestionEditbyAdvertismentId($id, $pid);
			break;	
	case 'arrange-sort' :
				$q = (isset($_REQUEST['q']))? $_REQUEST['q'] : '';
				$id=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';	
				$question->sortQuestionOrder($id, $q);	
			break;
	case 'add-question': 
				$id=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';	
				$pid=(isset($_REQUEST['pid']))? $_REQUEST['pid'] : '';
				echo $question->addQuestionById($id,$pid);
			break;
	case 'get-question-details':
				$name=(isset($_REQUEST['id']))? $_REQUEST['id'] : '';
				echo $question->getQuestionDetailsByType($name);
			break;
	case 'set-tracking':
				$qid = (isset($_REQUEST['qid']))? $_REQUEST['qid'] : ''; //page id 
				$pid=(isset($_REQUEST['pid']))? $_REQUEST['pid'] : '';	//question id
				$tid=(isset($_REQUEST['tid']))? $_REQUEST['tid'] : '';	//tracking id
				$question->sortQuestionOrder($pid, $qid, $tid);	
	default : echo ("No action given");
}
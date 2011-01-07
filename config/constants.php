<?php

define('DEFAULT_YEAR',date('Y'));

//MAIN database information
define('MAIN_DB_USER','root');
define('MAIN_DB_PASS','password');
define('MAIN_DB_HOST','dev');
define('MAIN_DB_DBASE','people_scope_main');
define('MAIN_DB_TYPE','mysql');

//Pagination settings
define('NUM_PER_PAGE', 10);

//SET MAIL SENDING TYPE mail, smtp, sendmail
define('MAILTYPE', 'mail');

//system type template settings
define('TEMPLATE_BLANK', 1);

//Utils Setting
define('TURN_ON_PP',true); //Toggle display of pp() function

define('SEO_LINK',false);

//default question list...question at are always added but can be removed
//define('DEFAULT_QUESTIONS', "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22");
define('DEFAULT_OFFICE_QUESTIONS', "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,18,19,20,22,25");
define('DEFAULT_STORE_QUESTIONS', "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,21,22,23,24,25");

//default question Manadtory question list...these question must appear on every form they are sed to track there details
// they can never be removed 1 first name 2 surname 6 emails
define('MANDATORY_QUESTIONS', "1,2,6");

//required question list...question at are always added but can NOT be removed
// NOTE any changes made here will effect any INSERTS in class/application.class.php saveApplicant()
define('REQUIRED_QUESTIONS', "1,2,3,6,7,12,13,15,16,18,19,20");  //21 was removed

//This is the four sub questions, they have there owne form in form.class.php eduction/referee/history/hours
define('EDU_SUBFORM', 15);
define('HISTORY_SUBFORM', 22);
define('HOURS_SUBFORM', 24);
define('REFEREE_SUBFORM', 25);

//This is the question about other stores to working in, over write standard question listing with rec_stores
define('STORE_LIST', 23);

define('SUPPORT_EMAIL', 'noreplycareers@forevernew.com.au');

//DEFINE TYPE office or store
define('OFFICE', 1);
define('STORE', 2);

//DEFINE index page
define('INDEX_PAGE','index.php');

//DEFINE access LEVEL
define('FULL_ADMIN',1);
define('STATE_ADMIN',2);
define('BUSINESS_ADMIN',3);

define('EDU_ROWS', 5);

//Defime Name that will always show in test perview bulk emails
define('EMAIL_TEST_FIRST' , 'wersal');
define('EMAIL_TEST_LAST' , 'numbspa');
define('EMAIL_TEST_EMAIL' , 'w.numbspa@wersal.com.au');

//DEFAULT email details if missing from rec_contacts table
define('DEFUALT_EMAIL_NAME' , 'Forever new');
define('DEFUALT_EMAIL' , 'recuitment@forevernew.com.au');

//Define which status is Susseccefull
define('STATUS_SUCCESSFUL' , 8);

//email me Jobs standard thank you email for epression od interest
define('EXPRESSION_EMAIL' , 15);
//email me Jobs standard 6 WEEK  email for epression od interest
define('EXPRESSION_EMAIL_6_WEEK' , 18);
//email me Jobs standard 12 WEEK email for epression od interest
define('EXPRESSION_EMAIL_12_WEEK' , 19);

//email me Jobs standard email id
define('EMAILMEJOBS' , 16);

//email job Standard thank you email for application
define('APP_EMAIL' , 17);

//email job Standard COPY email for application
define('COPY_EMAIL' , 20);

//DOCUMENT UPLOAD DIRECTORY
define('DOCUMENT_UPLOAD', $_SERVER['DOCUMENT_ROOT'].'/careers/admin/doc_uploads/');

//DEFINE REPORTING questions
define('REPORT_STATE',10);
define('REPORT_VISA',13);
define('REPORT_EDU',14);
define('REPORT_RETAIL',17);


?>
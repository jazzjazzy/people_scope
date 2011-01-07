<?php
/**
 * Template Class, 
 * <br />
 * This class is used to take a input to generate templates<br /> 
 * 
 * Example:<br />
 * <br />
 * $template = new template('template/index.html');<br />
 * <br />
 * $template->assign('var1', 'Employment list');<br />
 * $template->assignArray(array{'var2'=>'John', 'var3'=>'mary', 'var4'=>'<strong>frank</strong>'});<br />
 * <br />
 * $ArrayVars[0]['name'] = 'john';<br />
 * $ArrayVars[0]['age'] = '14';<br />
 * $ArrayVars[1]['name'] = 'mary';<br />
 * $ArrayVars[1]['age'] = '42';<br />
 * $ArrayVars[2]['name'] = 'frank';<br />
 * $ArrayVars[2]['age'] = '98';<br />
 * <br />
 * $template->assignRepeat('agelist', $ArrayVars);<br />
 * $template->replace('../', '../../');<br />
 * <br />
 * $template->display();<br />
 * <br />
 *  
 * @author Jason Stewart <jason@lexxcom.com.au>
 * @version 1.0
 * @package PeopleScope
 */

/**
 * required error handler 
 */
require_once 'errorhandler.class.php';

/**
 * This class is used to take a input to generate templates
 * 
 * @package PeopleScope
 * @subpackage Base
 */
class template {
	/**
	 * Location of template file
	 * @var String 
	 */
	var $template_file;
	
	/**
	 * array of assigned values to a template
	 * @var Array
	 */
	var $assigned = array();
	
	/**
	 * Array of values that should be replaced in template
	 * @var Array 
	 */
	var $replacer = array();
	
	/**
	 * Array of Reapeat region 
	 * @todo should be removed as we are not using Dreamweaver template anymore  
	 * @var Array
	 */
	var $repeatRegion=array();
	
	/**
	 * Contructor for template class 
	 * 
	 * @param String $file Path to current template file
	 * @return true 
	 */
	public function __construct($file=null){
		$this->template($file);
		return true;
	}
	
	/**
	 * Contructor php4 for template class 
	 * 
	 * @param String $file Path to current template file 
	 * @return true
	 */
	public function template($file=null){
		if($file){
			$this->set($file);
		}
		return true; 
	}

	/**
	 * Set the current template path to a new template file
	 * 
	 * @param String $file new path to template
	 * @return true 
	 */
	public function set($file){

		if(!is_file($file)){
			throw new CustomException($file.': file not found');
		}
		$filetmp = file($file);
		$fullfile ='';
		foreach($filetmp as $value){
			$fullfile .= $value."\n";
		}
		$this->template_file = $file;
		return true;
		
		define(DEBUG, true);
	}
	
	/**
	 * Used to assign a value element to a generated template
	 * 
	 * @param String $name name of element 
	 * @param String $content content to be replced with
	 * @return true
	 */
	public function assign($name, $content){
		$this->assigned[$name] = $content;
		return true;
	}

	/**
	 * Assign Blank is used if not using a template, example such as XML output 
	 * 
	 * example:<br />
	 * 
	 * $template = new template();<br />
	 * $template2->assignBlank('This is a test');<br />
	 * 
	 * @param String $content element to be displayed
	 * @return true;
	 */
	public function assignBlank($content){
		$this->assigned['blank'] = $content;
		return true;
	}
	
	/**
	 * Will assign to a repeating element from an assigned array <br />
	 * bassed on array row and element name eg.
	 *  <br />
	 * <code>
	 * array{
	 * 		[0]=> array{ 
	 * 				[name]=>'john'
	 * 				[age]=>'14' 
	 * 		} 
	 * 		[1]=> array{
	 * 				[name]=>'simon'
	 * 				[age]=>'23' 
	 * 		}
	 * }
	 * </code>
	 * 
	 * @param String $name Repeat assignment name  
	 * @param Array $content Array of arrays values
	 * @return true 
	 */
	public function assignRepeat($name, $content){
		$this->repeatRegion[$name] = $content;
		return true;
	}
	
	/**
	 * An Array of elements to be added to the template 
	 * array{'jason'=>'john', 'age'=>'14'}
	 * 
	 * @param Array $assignedArray array of element to be displayed
	 * @return true 
	 */
	public function assignArray($assignedArray){
		$this->assigned = $assignedArray;
		return true;
	}

	/**
	 * Will replace the string across the entire template  
	 * 
	 * replace happens before compiling, so it is possible to change a tag on the fly
	 * 
	 * @param String $string Sting to find
	 * @param String $value Value to be replaced with 
	 * @return true
	 */
	public function replace($string, $value=''){
		$this->replacer[]= array('string'=>$string,'value'=>$value);
		return true;
	}

	/**
	 * Build template with assigned values
	 * 
	 * @param $content Content of the template 
	 * 
	 * @return Sting HTML Generated from gathered information   
	 */
	private function BuildAssigned($content) {
		
		// default add jquery to the head of the file
		preg_match_all( '/<head>(.*)<\/head>/siU' , $content, $head);

		$newheader = '<head><script type="text/javascript" src="/js/jquery.js"></script>'."\n".$head[1][0]."</head>";
		$content = preg_replace( '/<head>(.*)<\/head>/siU' , $newheader, $content);
		
		// default add debuging tool to the body of the file
		preg_match_all( '/<body(.*)>/siU' , $content, $body);
		$debug = $body[0][0].showVars();
		$content = preg_replace( '/<body(.*)>/siU' , $debug, $content);
		
		if( preg_match( '/\{\*repeat\=(.*?)\*\}/', $content ) ){
				
				preg_match_all( '/\{\*repeat\=(.*?)\*\}/' , $content, $variable);
				
				foreach($variable[1] AS $value){
					$compiledtemplate ='';
					// lets get our template  
					preg_match_all( '/\{\*repeat\='.$value.'\*\}(.*)\{\*\/repeat\*\}/siU' , $content, $repeatContent);
					$rtemplate = $repeatContent[1][0];
					
					// does the section of template have an assigment var 
					if( preg_match( '/\{\*(.*?)\*\}/', $rtemplate) ){
						preg_match_all( '/\{\*(.*?)\*\}/' , $rtemplate, $vars); //get list of vars
						//get the var set for this section of template
						foreach($this->repeatRegion[trim($value)] AS $rs){
							$rt = $rtemplate;
							//Merge vars to with content 
							foreach($vars[1] AS $key=>$assignname){
									$rt =str_replace($vars[0][$key], $rs[$assignname], $rt );
							}
							$compiledtemplate .= $rt."<br />";
						}
					}
					$content = preg_replace( '/\{\*repeat\='.$value.'\*\}(.*)\{\*\/repeat\*\}/siU' , $compiledtemplate, $content);
				}
		}
		
		
		if( preg_match( '/\{\*(.*?)\*\}/', $content ) ){
			
			preg_match_all( '/\{\*(.*?)\*\}/' , $content, $variable);
			
			foreach($variable[0] AS $key=>$val){
				if (isset($this->assigned[trim($variable[1][$key])])){
					$content =str_replace($variable[0][$key], $this->assigned[trim($variable[1][$key])], $content );
				}
			}
		}
		
		if( preg_match( '/\{\?(.*?)\?\}/', $content ) ){
			
			preg_match_all( '/\{\?(.*?)\?\}/' , $content, $code);
			
			foreach($code[0] AS $key=>$val){
				ob_start();
				eval($code[1][$key]);
				$result = ob_get_contents();
				ob_clean();
				$content =str_replace($code[0][$key], $result, $content );
			}
		}	
		
		return $content;
	}
	
	/**
	 * Will return the full generated page template as a variarable 
	 * 
	 * @return Sting html Generated from gathered information
	 */
	public function fetch(){
		if($this->template_file){
			if(!$template = file_get_contents($this->template_file)){
				throw new CustomException('$this->template_file: Unable to read file contents');
			}
			
			foreach($this->replacer AS $key=>$value){
				$template = str_replace($value['string'], $value['value'], $template);
			}
			
			$template=$this->BuildAssigned($template);
			

			if(DEBUG){
				$body = parseArray($template, '<body', ">");
				$template = str_replace($body, showVars(), $template);
			}
		}else{
			if(!DEBUG){
				$vars = showVars();
				$template = '<html><body>'.$vars.$this->assigned['blank'].'</body></html>';
			}else{
				$template = $this->assigned['blank'];
			}
		}
		return $template;
	}

	/**
	 * Will print out the full generated page template 
	 * 
	 */
	public function display(){
		echo $this->fetch();
	}

}
?>
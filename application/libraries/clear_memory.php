<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clear_memory
{
	protected 	$ci;

	public function __construct()
	{
		$this->ci =& get_instance();
	}

	function clean_all(&$items,$leave = ''){
		foreach($items as $id => $item){
			if($leave && ((!is_array($leave) && $id == $leave) || (is_array($leave) && in_array($id,$leave)))) continue;
			if($id != 'GLOBALS'){
				if(is_object($item) && ((get_class($item) == 'simple_html_dom') || (get_class($item) == 'simple_html_dom_node'))){
					$items[$id]->clear();
					unset($items[$id]);
				}else if(is_array($item)){
					$first = array_shift($item);
					if(is_object($first) && ((get_class($first) == 'simple_html_dom') || (get_class($first) == 'simple_html_dom_node'))){
						unset($items[$id]);
					}
					unset($first);
				}
			}
		}
	}	

}

/* End of file clear_mem.php */
/* Location: ./application/libraries/clear_mem.php */

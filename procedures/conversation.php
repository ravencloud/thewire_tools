<?php 

	$guid = sanitise_int(get_input("guid", 0), false);

	if(($wire = get_entity($guid)) && elgg_instanceof($wire, "object", "thewire")){
		
		if(!empty($wire->conversation)){
			$options = array(
				"type" => "object",
				"subtype" => "thewire",
				"limit" => false,
				"metadata_name_value_pairs" => array("conversation" => $wire->conversation),
				"wheres" => array("(e.guid < " . $wire->getGUID() . ")")
			);
			
			if($entities = elgg_get_entities_from_metadata($options)){
				if($start = get_entity($wire->conversation)){
					$entities[] = $start;
				}
			} else {
				if($start = get_entity($wire->conversation)){
					$entities = array($start);
				}
			}
		} else {
			$entities = array($wire);
		}
		
		if(!empty($entities)){
			// set context to conversation mode
			elgg_set_context("thewire_tools_conversation");
			
			echo elgg_view_entity_list($entities, count($entities), 0, count($entities), false, false, false);
			
			// restore context
			elgg_pop_context($context);
		} else {
			echo elgg_echo("notfound");
		}
	} else {
		echo elgg_echo("notfound");
	}
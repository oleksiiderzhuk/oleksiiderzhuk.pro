<?php
/*
 * Copyright (c) 2022 LatePoint LLC. All rights reserved.
 */

namespace LatePoint\Addons\CustomFields;

class CustomField{
	public $id;
	public $label;
	public $type;
	public $value;
	public $required = 'off';
	public $width = 'os-col-12';
	public $placeholder;
	public $options;

	function __construct($args = []){
		$allowed_props = self::allowed_props();
		foreach($args as $key => $arg){
			if(in_array($key, $allowed_props)) $this->$key = $arg;
		}
	}


	public static function allowed_props(): array{
		return ['label',
						'type',
						'value',
						'required',
						'width',
						'placeholder',
						'options'];
	}

}
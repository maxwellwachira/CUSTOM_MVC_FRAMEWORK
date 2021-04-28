<?php

namespace app\core;

abstract class Model
{
	public CONST RULE_REQUIRED = 'required';
	public CONST RULE_EMAIL = 'email';
	public CONST RULE_MIN = 'min';
	public CONST RULE_MAX = 'max';
	public CONST RULE_MATCH = 'match';

	
	public array $errors = [];
	public function loadData($data)
	{
		foreach ($data as $key => $value) {
			if(property_exists($this, $key)){
				$this->{$key} = $value;
			}
		}
	}

	//abstract public function rules(): array;

	public function validate(){
		foreach ($this->rules() as $attribute => $rules) {
			$value = $this->{$attribute};
			foreach ($rules as $rule) {
				$ruleName = $rule;
				if(!is_string($ruleName)){
					$ruleName = $rule[0];
				}
				if($ruleName === self::RULE_REQUIRED && !$value){
					$this->addError($attribute, self::RULE_REQUIRED);
				}
				if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
					$this->addError($attribute, self::RULE_EMAIL);
				}
				if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
					$this->addError($attribute, self::RULE_MIN);
				}
				if($ruleName === self::RULE_MATCH && $value !== $rule['match']){
					$this->addError($attribute, self::RULE_MATCH);
				}
			}
		}
		return empty($this->errors);
	}

	public function addError(string $attribute, string $rule){
		$message = $this->errorMessages()[$rule] ?? '';
		$this->errors[$attribute][] = $message;

	}
	public function errorMessages(){
		return [
			self::RULE_REQUIRED => 'This field is required',
			self::RULE_EMAIL => 'This field must be a valid email address',
			self::RULE_MIN => 'Minimum length of this field must be 8',
			self::RULE_MATCH => 'This field should be the same as Password'

		];
		
	}

	public function hasError($attribute){
		return $this->errors[$attribute] ?? false;
	}
}
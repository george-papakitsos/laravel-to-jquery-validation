<?php

namespace GPapakitsos\LaravelToJqueryValidation\Tests;

use PHPUnit\Framework\TestCase;
use GPapakitsos\LaravelToJqueryValidation\Validation;

class Test extends TestCase
{
	const LARAVEL_VALIDATION_RULES = [
		'email' => 'required|email|max:255',
		'website' => 'url|min:5',
		'dob' => 'date_format:d/m/Y',
		'singUpDate' => 'date_format:Y-m-d',
		'singUpTime' => 'date_format:H:i',
		'age' => 'numeric|between:18,99',
		'vat' => 'digits:9',
		'mobile' => 'digits_between:5,10',
	];
	const JQUERY_VALIDATION_RULES = '{'.
		'"email":{"required":true,"email":true,"maxlength":255},'.
		'"website":{"url":true,"minlength":5},'.
		'"dob":{"dateITA":true},'.
		'"singUpDate":{"dateISO":true},'.
		'"singUpTime":{"time":true},'.
		'"age":{"number":true,"range":[18,99]},'.
		'"vat":{"digits":true,"minlength":9,"maxlength":9},'.
		'"mobile":{"digits":true,"rangelength":[5,10]}'.
	'}';
	const REQUEST_DATA = [
		'email' => 'papakitsos_george@yahoo.gr',
		'website' => 'https://papakitsos.gr',
		'dob' => '23/04/1981',
		'singUpDate' => '2000-04-23',
		'singUpTime' => '07:10',
		'age' => '39',
		'vat' => '123456789',
		'mobile' => '6937102030',
	];

	public function testConvertRules()
	{
		$validator = (new ValidatorFactory())->make(self::REQUEST_DATA, self::LARAVEL_VALIDATION_RULES);
		$this->assertTrue($validator->passes());

		$this->assertEquals(self::JQUERY_VALIDATION_RULES, Validation::convertRules(self::LARAVEL_VALIDATION_RULES));
	}
}

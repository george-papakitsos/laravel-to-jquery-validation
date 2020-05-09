<?php

/**
 * Converts laravel validation rules to jQuery validation rules
 *
 * @author George Papakitsos <papakitsos_george@yahoo.gr>
 * @copyright George Papakitsos
 */

namespace GPapakitsos\LaravelToJqueryValidation;

use Illuminate\Support\Str;

class Validation {

	private const LARAVEL_RULES_TO_JQUERY_RETURN_TRUE = [
		'required' => 'required',
		'email' => 'email',
		'url' => 'url',
		'numeric' => 'number',
		'date_format:d/m/Y' => 'dateITA',
		'date_format:Y-m-d' => 'dateISO',
		'date_format:H:i' => 'time',
	];
	private const LARAVEL_RULES_TO_JQUERY_RETURN_INT = [
		'min:' => 'minlength',
		'max:' => 'maxlength',
	];

	/**
	 * Converts laravel validation rules to JSON object, readable from jQuery validation plugin
	 *
	 * @param array $laravelRules
	 *
	 * @return string
	 */
	public static function convertRules($laravelRules)
	{
		$rulesJS = [];

		foreach ($laravelRules as $fieldName => $fieldRules)
		{
			$rulesJS[$fieldName] = [];
			$fieldRules = explode('|', $fieldRules);

			foreach ($fieldRules as $rule)
			{
				if (array_key_exists($rule, self::LARAVEL_RULES_TO_JQUERY_RETURN_TRUE))
				{
					$rulesJS[$fieldName][self::LARAVEL_RULES_TO_JQUERY_RETURN_TRUE[$rule]] = true;
					continue;
				}

				foreach (self::LARAVEL_RULES_TO_JQUERY_RETURN_INT as $laravelRule => $jqueryRule)
				{
					if (Str::startsWith($rule, $laravelRule))
					{
						$rulesJS[$fieldName][$jqueryRule] = (int) Str::after($rule, $laravelRule);
						continue 2;
					}
				}

				if (Str::startsWith($rule, 'between:'))
				{
					$ruleValue = Str::after($rule, 'between:');
					$range = explode(',', $ruleValue);
					$rulesJS[$fieldName]['range'] = [(float) $range[0], (float) $range[1]];
					continue;
				}

				if (Str::startsWith($rule, 'digits:'))
				{
					$ruleValue = (int) Str::after($rule, 'digits:');
					$rulesJS[$fieldName]['digits'] = true;
					$rulesJS[$fieldName]['minlength'] = $ruleValue;
					$rulesJS[$fieldName]['maxlength'] = $ruleValue;
					continue;
				}

				if (Str::startsWith($rule, 'digits_between:'))
				{
					$ruleValue = Str::after($rule, 'digits_between:');
					$range = explode(',', $ruleValue);
					$rulesJS[$fieldName]['digits'] = true;
					$rulesJS[$fieldName]['rangelength'] = [(int) $range[0], (int) $range[1]];
					continue;
				}

			}
		}

		return json_encode($rulesJS);
	}

}

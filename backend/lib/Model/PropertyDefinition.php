<?php
/**
 * @copyright Copyright (c) 2010, The volkszaehler.org project
 * @package default
 * @license http://www.opensource.org/licenses/gpl-license.php GNU Public License
 */
/*
 * This file is part of volkzaehler.org
 *
 * volkzaehler.org is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * volkzaehler.org is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with volkszaehler.org. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Volkszaehler\Model;

use Volkszaehler\Util;

class PropertyDefinition extends Util\Definition {
	/** One of: string, numeric, multiple */
	public $type;

	/** @var string regex pattern to match if type == string */
	protected $pattern;

	/**
	 * Minimal value if type == integer or type == float
	 * Required string length if type == string
	 *
	 * @var integer|float
	 */
	protected $min;

	/**
	 * Maximal value if type == integer or type == float
	 * Allowed string length if type == string
	 *
	 * @var integer|float
	 */
	protected $max;

	/**
	 * List of possible choices if type == multiple
	 * (type as in javascript: 1.2 => float, 5 => integer, "test" => string)
	 *
	 * @var array
	 */
	protected $choices = array();


	/**
	 * File containing the JSON definitons
	 *
	 * @var string
	 */
	const FILE = '/share/properties.json';

	/**
	 * Validate value according to $this->type
	 *
	 * @param string|numeric $value
	 * @return boolean
	 */
	public function validateValue($value) {
		switch ($this->type) {
			case 'string':
				$invalid = !is_string($value);
				$invalid |= isset($this->pattern) && !preg_match($this->pattern, $value);
				$invalid |= isset($this->min) && strlen($value) < $this->min;
				$invalid |= isset($this->max) && strlen($value) > $this->max;
				break;

			case 'integer':
				$invalid = !is_int($value);
				break;

			case 'float':
				$invalid = !is_float($value);
				break;

			case 'multiple':
				$invalid = !in_array($value, $this->choices, TRUE);
				break;

			default:
				throw new \Exception('unknown property type');
		}

		if ($type == 'integer' || $type == 'float') {
			$invalid |= isset($this->min) && $value < $this->min;
			$invalid |= isset($this->max) && $value > $this->max;
		}

		return !$invalid;
	}
}

?>
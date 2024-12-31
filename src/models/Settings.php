<?php

namespace ttempleton\categorygroupsfield\models;

use craft\base\Model;

/**
 * Category Groups Field plugin settings class.
 *
 * @package ttempleton\categorygroupsfield\models
 * @author Thomas Templeton
 * @since 1.1.0
 */
class Settings extends Model
{
	public bool $singleSelectionDefault = false;

	public function rules(): array
	{
		return [
			[['singleSelectionDefault'], 'boolean'],
		];
	}
}

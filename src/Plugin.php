<?php

namespace ttempleton\categorygroupsfield;

use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use ttempleton\categorygroupsfield\fields\CategoryGroupsField;
use ttempleton\categorygroupsfield\models\Settings;
use yii\base\Event;

/**
 * Main Category Groups Field plugin class.
 *
 * @package ttempleton\categorygroupsfield
 * @author Thomas Templeton
 * @since 1.0.0
 */
class Plugin extends BasePlugin
{
	/**
	 * Initialises the Category Groups Field plugin.
	 */
	public function init(): void
	{
		parent::init();

		Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
			$event->types[] = CategoryGroupsField::class;
		});
	}

	/**
	 * @inheritdoc
	 */
	public function createSettingsModel(): ?Model
	{
		return new Settings();
	}
}

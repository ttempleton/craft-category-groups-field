<?php

declare(strict_types=1);

use craft\ecs\SetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function(ECSConfig $ecsConfig): void {
	$ecsConfig->parallel();
	$ecsConfig->paths([
		__DIR__ . '/src',
		__FILE__,
	]);
	$ecsConfig->sets([SetList::CRAFT_CMS_4]);
	$ecsConfig->indentation(Option::INDENTATION_TAB);
};

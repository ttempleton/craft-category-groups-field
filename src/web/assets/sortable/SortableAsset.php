<?php

namespace ttempleton\categorygroupsfield\web\assets\sortable;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Asset bundle class for including the jQuery UI Sortable widget, for use with multi-select category groups fields.
 *
 * @package ttempleton\categorygroupsfield\web\assets\sortable
 * @author Thomas Templeton
 * @since 2.1.0
 */
class SortableAsset extends AssetBundle
{
    public function init(): void
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'dist';
        $this->depends = [
            CpAsset::class,
        ];
        $this->js = [
            'jquery-ui.min.js',
        ];

        parent::init();
    }
}

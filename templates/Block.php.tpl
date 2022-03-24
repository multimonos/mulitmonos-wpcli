<?php

namespace Glance\Blocks\%%classname%%;

use Glance\Blocks\Block;

class %%classname%% extends Block
{
    public function template(): string {
        // requires the lib/Glance/Blocks folder to be in twig path
        // ie, Timber::$dirname = array('templates', 'views', 'lib/Glance/Blocks');
        return  '%%classname%%/%%slug%%.twig';
    }

    public function get_acf_block_config(): array {
        return [
            'mode'       => 'edit',
            'name'       => '%%block_name%%',
            'title'      => '%%block_title%%',
            'post_types' => [%%post_types%%],
            'multiple'   => false,
            'category'   => '%%block_category%%',
            'keywords'   => [%%block_keywords%%],
            ];
    }
}
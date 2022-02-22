<?php

namespace Glance\Blocks\Block;

use Glance\Blocks\Block;

class %%classname%% extends Block
{
    public function template(): string {
        return __DIR__ . '/%%slug%%.twig';
    }

    public function get_acf_block_config(): array {
        return [
            'mode'       => 'edit',
            'name'       => '%%block_name%%',
            'title'      => '%%block_title%%',
            'post_types' => [],
            'multiple'   => false,
            'category'   => 'formatting',
            'keywords'   => [],
            ];
    }
}
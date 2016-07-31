<?php

namespace Dyusha\HtmlEditor;

use Dyusha\HtmlEditor\Models\Block;
use Illuminate\Support\Facades\Gate;

class HtmlBlocks
{
    
	protected static $models = [];

    /**
     * Setup block
     *
     * @param mixed $slug
     * @param string $title
     * @return boolean
     */
    public static function setUp($slug, $title = '')
    {
    	self::$models[$slug] = $title;

        ob_start();

        return !! Block::where('slug', $slug)->first();
    }

    /**
     * Teardown block setup
     */
    public static function tearDown()
    {
        $html = ob_get_clean();

        end(self::$models);
        $slug = key(self::$models);
        $title = array_pop(self::$models);

        /** @var Block $block */
        $block = Block::where('slug', $slug)->first();

        if (!$block) {
            $block = new Block([
                'slug' => $slug,
                'title' => $title,
                'content' => trim($html),
            ]);

            $block->save();
        }

        if (Gate::allows('edit-html-blocks')) {
            return sprintf('<html-block slug="%s">%s</html-block>', $slug, trim($block->content));
        }

        return trim($block->content);
    }
}
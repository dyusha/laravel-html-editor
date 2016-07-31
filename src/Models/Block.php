<?php

namespace Dyusha\HtmlEditor\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Block
 * @package Dyusha\HtmlEditor\Models
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $content
 */
class Block extends Model
{
    public $table = 'html_blocks';

    protected $fillable = [
        'slug', 'title', 'content'  
    ];
    
    /**
     * Renders content of the given block
     * 
     * @param  string $slug Unique slug
     * @return string
     */
    public static function render($slug)
    {
        /** @var Block $block */
        $block = Block::where('slug', $slug)->first();

        if (!$block) {
            return '';
        }

        return $block->content;
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $this->cleanupContent($value);
    }

    /**
     * Removes all unnecessary strings from input content
     * 
     * @param  string $string New block content
     * @return string
     */
    protected function cleanupContent($string)
    {
        $string = trim(str_replace('  ', ' ', str_replace('&nbsp;', ' ', $string)));

        $vueJsDebugStrings = ['<!--v-start-->', '<!--v-end-->', '<!--v-component-->'];
        $string = str_replace($vueJsDebugStrings, '', $string);

        return trim($string);
    }
}
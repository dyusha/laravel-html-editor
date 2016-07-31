Inline edit HTML blocks directly on a page
================
This package adds an ability to inline edit any defined HTML block in your Laravel app.
It uses awesome [MediumEditor](https://github.com/yabwe/medium-editor) wrapped into [Vue.js](http://vuejs.org/) components.

## Installation

Install this package via composer:

`composer require dyusha/laravel-html-editor`

Install required npm packages:

`npm install vue vue-resource medium-editor --save`

Add service provider to your config file:

```php
// config/app.php
'providers' => [
    ...
    Dyusha\HtmlEditor\HtmlBlocksProvider::class,
],
```

After that you will be able to publish config, migrations, views and needed assets.

By default js and sass assets will be published to `/resources/assets/js/components` and `/resources/assets/sass/plugins` directories respectively. In order to override these settings you need to publish config file.

`php artisan vendor:publish --provider="Dyusha\HtmlEditor\HtmlBlocksProvider" --tag=config`

and change following paths

```php
// config/html-editor.php
'paths' => [
    'js' => base_path('/resources/assets/js/components'),
    'sass' => base_path('/resources/assets/sass/plugins'),
],
```

Publish remaining assets and migrations:

`php artisan vendor:publish --provider="Dyusha\HtmlEditor\HtmlBlocksProvider"`

Apply migrations:

`php artisan migrate`

## Usage

This package provides custom Blade directives `@block` and `@endblock` which can be used to wrap blocks of HTML that should be editable. For example if somewhere in your template you will have the following code

```html
@block('hero-text', 'Homepage hero text')
   <h1>Lorem ipsum dolor sit amet</h1>
@endblock
```

the first time it's being rendered directive will try to find HTML block with slug `hero-text` in the database. If it is present then its content will be rendered on the page. Otherwise new HTML block will be created with slug `hero-text`, optional description `Homepage hero text` and content `Lorem ipsum dolor sit amet`. You can put any HTML markup between `@block` and `@endblock` directives.

### In order to edit such blocks you need to follow few steps:

1. Somewhere in your layout add partial that will render required controls

    `@include('html-editor::html-manager')`

2. By default editing is allowed only for users who have `edit-html-blocks` ability so you should add it in your `AuthServiceProvider`
    
    ```php
    // app/Providers/AuthServiceProvider.php
    
    public function boot(GateContract $gate)
    {
        $gate->define('edit-html-blocks', function ($user) {
            // Add your logic here
            return true;
        });
    }
    ```

3. Include provided scss and js files on the page using your preferred build tools
4. Include Vue.js components in you root instance or another component:
```js
// resources/assets/js/app.js

var Vue = require('vue');

new Vue({
    el: 'body',

    components: {
        htmlManager: require('./components/cms/manager'),
        htmlBlock: require('./components/cms/block'),
    },
});
```

You can learn more about Vue.js components [here](http://vuejs.org/guide/components.html).

At this point all HTML blocks wrapped in `@block` directive should be rendered on a page as `<html-block>` component and be editable:

```html
<html-block slug="hero-text">
   <h1>Lorem ipsum dolor sit amet</h1> 
</html-block>
```

### Updating blocks

When you press `Accept changes` button `<html-manager>` component will send `POST` request to `/admin/blocks` with `blocks` param that will contain all changed HTML blocks.

#### License
This library is licensed under the MIT license. Please see [LICENSE](LICENSE.md) for more details.

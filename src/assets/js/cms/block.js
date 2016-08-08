var Vue = require('vue/dist/vue');
var MediumEditor = require('medium-editor/dist/js/medium-editor');

module.exports = Vue.extend({
    props: ['slug'],

    data: function () {
        return {
            medium: null,
            oldHtml: '',
        }
    },

    ready: function() {
        this.medium = new MediumEditor(this.$el.parentElement, {
            anchor: {
                targetCheckbox: true,
            },
        });

        this.oldHtml = this.medium.getContent();
    },
    
    methods: {
        getContent: function () {
            return this.medium.getContent();
        },

        syncContent: function() {
            this.oldHtml = this.getContent();
        },

        hasChanged: function () {
            return this.getContent() != this.oldHtml;
        }
    },
});

var Vue = require('vue/dist/vue');

module.exports = Vue.extend({
    methods: {
        applyChanges: function () {
            var changed = [],
                changedBlocks = [],
                blocks = this.getBlockComponents(this.$root);

            blocks.forEach(function (block) {
                if (block.hasChanged() == false) {
                    return true;
                }

                changed.push({
                    slug: block.slug,
                    content: block.getContent(),
                });

                changedBlocks.push(block);
            });

            if (changed.length == 0 || !confirm('Apply changes?')) {
                return;
            }

            this.$http.post('/admin/blocks', { blocks: changed })
                .then(function (response) {
                    if (response.status == 200) {
                        this.syncBlocksContent(changedBlocks);
                    } else {
                        console.warn('Error occurred');
                    }
                });
        },

        syncBlocksContent: function(blocks) {
            blocks.forEach(function (block) {
                block.syncContent();
            });
        },

        getBlockComponents: function(parent) {
            var blocks = [];

            parent.$children.forEach(function (component) {
                if (component.$options.name == 'html-block') {
                    blocks.push(component);
                    return;
                }

                if (component.$children.length > 0) {
                    var childrenBlocks = this.getBlockComponents(component);

                    childrenBlocks.forEach(function (block) {
                        blocks.push(block);
                    });
                }
            }.bind(this));

            return blocks;
        }
    },

});

<template>
    <div :class="'gallery' + (gallery.thumbnail ? ' is-image' : ' not-image')" :style="{ backgroundImage: (gallery.thumbnail ? 'url(\'' + addslashes(gallery.thumbnail) + '?' + thumbnailVersion + '\')' : 'none') }" :title="gallery.file_name">
        <div v-if="!gallery.thumbnail" class="file-name" :msg="gallery.file_name" :line="4">
            {{ gallery.file_name }}
        </div>
        <div class="btn-remove" @click="remove" title="Remove">
            <i class="fa fa-close"></i>
        </div>
        <a class="btn-open" :href="gallery.path" target="_blank" title="Open In New Window">
            <i class="fa fa-external-link"></i>
        </a>
     
        <input type="hidden" :name="inputName" :value="gallery.id">
    </div>
</template>


<script>
    export default {
        props: ['gallery', 'field', 'isImage'],
        data: function() {
            return {
                thumbnailVersion: 0
            }
        },
        computed: {
            galleryList: function () {
                if(this.isImage){
                    return this.$parent.$parent.$parent;
                }
                return this.$parent.$parent;
            },
            inputName: function(){
                if(this.galleryList.isRepeater){
                    return 'repeater[' + this.galleryList.mainField +  '][' + this.galleryList.rIndex + '][' + this.field +  '][' + this.galleryList.languageId + '][' + this.galleryList.type + '][]';
                }

                return this.field + '[' + this.galleryList.languageId + '][' + this.galleryList.type + '][]';
            }
        },
        methods: {
            addslashes: function(string){
                return string.replace(/\\/g, '\\\\').
                    replace(/\u0008/g, '\\b').
                    replace(/\t/g, '\\t').
                    replace(/\n/g, '\\n').
                    replace(/\f/g, '\\f').
                    replace(/\r/g, '\\r').
                    replace(/'/g, '\\\'').
                    replace(/"/g, '\\"');
            },
            remove: function(){
                var self = this;
                 _.remove(self.galleryList.mediasData, {
                    id: self.gallery.id
                });
                self.galleryList.mediasData = [
                   ...self.galleryList.mediasData
                ]
            }
        }
    }
</script>
<template>
    <div class="gallery-container">
        <input type="hidden" :name="inputName" value="" v-if="!isRepeater">
        <Photoswipe v-if="isImage">
            <draggable v-if="galleries.length > 0" class="gallery-list clearfix">
                <gallery v-for="gallery in galleries" :gallery="gallery" :key="gallery.id" :field="field" :is-image="isImage" v-pswp="gallery.path" @click.native="galleryClick"></gallery>
            </draggable>
        </Photoswipe>
        <div v-else>
            <draggable v-if="galleries.length > 0" class="gallery-list clearfix">
                <gallery v-for="gallery in galleries" :gallery="gallery" :key="gallery.id" :field="field" :is-image="isImage"></gallery>
            </draggable>
        </div>
        <a class="btn btn-primary" href="javascript:void(0);" @click="openMediaManager">{{ getButtonTitle() }}</a>
        <a v-if="!isSingle && galleries.length > 0" class="btn btn-danger" href="javascript:void(0);" @click="removeAll">Remove All</a>
        <div class="gallery-list-remark" v-if="!isSingle">Drag & Drop to change arrangement</div>
    </div>
</template>

<script>
    export default {
        components: {
            'gallery': require('./Gallery').default,
        },
        props: ['medias', 'isSingle', 'type', 'languageId', 'field', 'lastFolderId', 'buttonTitle', 'isVideo', 'isFile', 'isRepeater', 'mainField', 'rIndex'],
        data: function() {
            return {
                mediasData: []
            }
        },
        created: function(){
            this.mediasData = this.medias || [];
            this.lastGallery = _.last(this.galleries);
        },
        watch: {
            medias: function (val, oldVal) {
                if(val.length != 0 && this.isRepeater){
                    this.mediasData = val;
                }
            }
        },
        computed: {
            galleries: function(){
                return this.mediasData;
            },
            isImage: function(){
                return !this.isVideo && !this.isFile;
            },
            inputName: function(){
                if(this.isRepeater){
                    return 'repeater[' + this.mainField +  '][' + this.rIndex + '][' + this.field +  '][' + this.languageId + '][' + this.type + '][]';
                }

                return this.field + '[' + this.languageId + '][' + this.type + '][]';
            }
        },
        methods: {
            openMediaManager: function(){
                this.$root.openMediaManager(this, this.isSingle, this.lastGallery, this.lastFolderId);
            },
            arrangement: function(){

            },
            getButtonTitle: function(){
                if(this.buttonTitle && this.buttonTitle != ''){
                    return this.buttonTitle;
                }

                if(this.isVideo){
                    return this.isSingle ? 'Select Video' : 'Add Videos';
                }

                if(this.isFile){
                    return this.isSingle ? 'Select File' : 'Add Files';
                }

                return this.isSingle ? 'Select Image' : 'Add Images';
            },
            galleryClick: function(){
                $('.pswp__ui').removeClass('pswp__ui--hidden');
            },
            removeAll: function(){
                this.mediasData = [];
            }
        }
    }
</script>
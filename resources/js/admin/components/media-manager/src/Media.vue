<template>
    <div :class="'media-cell' + (mediaManager.isSingle ? ' is-single' : '')">
        <div class="media-cell-inner clearfix">
            <div class="checkbox-image-col clearfix" @click="select">
                <div class="checkbox-col" v-if="!mediaManager.isSingle">
                    <input type="checkbox" ref="checkbox" class="checkbox" v-model="checked">
                </div>
                <div class="image-col">
                    <div v-if="media.thumbnail" class="media-img" :style="{ backgroundImage: 'url(\'' + addslashes(media.thumbnail) + '?' + thumbnailVersion + '\')' }" :title="media.name"></div>
                    <div v-else class="media-img" :title="media.name">
                        {{ media.extension }}
                    </div>
                </div>
            </div>
            <div class="detail-col">
                <div class="filename" @click="detailPopup">{{ media.file_name }}</div>
                <div class="title" @click="detailPopup" style="display: none;">{{ media.title }}</div>
                <div class="delete" @click="remove">Delete</div>
                <div class="info clearfix">
                    <div class="created-at">{{ media.created_at}} </div>
                    <div class="size">{{ bytesToSize(media.size) }} </div> 
                </div>
            </div>
        </div>
        <media-detail-modal ref="detailModal" :media="media"></media-detail-modal>
    </div>
</template>
<script>
    import helper from '../config/helper';
    export default {
        props: ['media'],
        mixins: [helper],
        components: {
            'media-detail-modal': require('./MediaDetailModal').default,
        },
        computed: {
            mediaManager: function () {
                return this.$root.$refs.manager;
                // return this.$parent.$parent;
            },
            mediaList: function () {
                return this.mediaManager.$refs.mediaList;
            },
        },
        data: function() {
            return {
                checked: false,
                thumbnailVersion: 0
            }
        },
        created: function(){
            this.initData(this.media);
        },
        methods: {
            initData: function(media){
                Vue.set(media, 'vm', this); 
            },
            remove: function(){
                var self = this;
                var params = new URLSearchParams();
                params.append('id', this.media.id);
                axios.post(config.media_library.endpoints.delete_media, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                             _.remove(self.mediaList.medias, {
                                id: self.media.id
                            });
                            self.mediaList.medias = [
                               ...self.mediaList.medias
                            ]
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
            select: function(){
                this.checked = !this.checked;
                this.handleSelect();

                if(this.mediaManager.caller && this.mediaManager.isSingle){
                    return this.mediaManager.selectFilesToGallery();
                }
            },
            setChecked: function(checked){
                this.checked = checked;
                this.handleSelect();
            },
            handleSelect: function(){
                this.mediaList.switchDeleteBtn();
                this.mediaManager.selectedFiles = this.mediaList.getSelectedFiles();
            },
            isChecked: function(){
                return this.checked;
            },
            detailPopup: function(){
                this.$refs.detailModal.showModal();
            },
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
        }
    }
</script>

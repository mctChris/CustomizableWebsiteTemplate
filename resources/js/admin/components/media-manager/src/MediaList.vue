<template>
    <!-- <vue-dropzone ref="dropzone2" id="dropzone2" :options="dropzoneOptions"  :useCustomSlot="true"> -->
    <div class="media-list-container">
        <modal ref="moveFilesModal" :options="modalOptions">
            <v-jstree ref="tree2" :data="folders" text-field-name="name" @item-click="folderClick"></v-jstree>
        </modal>
        
        <div class="media-list-action-row" v-if="!mediaManager.isSingle">
            <ul class="action-list" v-if="medias.length > 0">
                <li class="checkbox-col"><input type="checkbox" @change="selectAll" v-model="checkedAll"></li>
                <li><button class="btn btn-sm btn-primary" v-model="isCheckedMedia" :disabled="!isCheckedMedia" @click="moveFilesPopup">Move</button></li></li>
                <li><button class="btn btn-sm btn-danger" v-model="isCheckedMedia" :disabled="!isCheckedMedia" @click="deleteFilesPopup">Delete</button></li></li>
            </ul>
        </div>
        
        <div v-if="medias.length > 0" class="media-list">
            <media v-for="media in medias" :media="media" :key="media.id"></media>
        </div>
        <div class="media-list-placeholder">
            <div v-if="mediaManager.$refs.tree.data[0].children.length == 0">
                Create a folder to store your first file
            </div>
            <div v-else-if="mediaManager.getSelectedFolder().is_root">
                Please choose a folder
            </div>
        </div>
    </div>
<!-- </vue-dropzone> -->
</template>

<script>
    export default {
        components: {
            'media': require('./Media').default,
        },
        computed: {
            mediaManager: function () {
                return this.$parent.$parent;

                // return this.$root.$refs.manager;
            },
        },
        data: function() {
            return {
                medias: [],
                isCheckedMedia: false,
                checkedAll: false,
                modalOptions: {
                    ok: function(){},
                    cancel: function(){}
                },
                folders: [],
                selectedFolder: null,
                dropzoneOptions: {
                    url: config.media_library.endpoints.upload,
                    thumbnailWidth: 90,
                    thumbnailHeight: 90,
                    headers: { "X-CSRF-TOKEN": config.csrf_token },
                    // dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Drop files here to upload"
                },
            }
        },
        created: function(){
            var self = this;
            // console.log(self.$root.$refs.manager);
            // setTimeout(function(){
            //     console.log(self.$root.$refs.manager);
            // })
            // console.log(this.$root.$refs.manager);
        },
        methods: {
            folderClick: function(node){
                this.selectedFolder = node.model;
                this.modalOptions.okDisabled = (this.selectedFolder.id == this.mediaManager.getSelectedFolder().id);
            },
            selectAll: function(){
                var self = this;
                this.medias.map(function(media){
                    media.vm.setChecked(self.checkedAll);
                });
            },
            unSelectAll: function(){
                var self = this;
                this.checkedAll = false;
                this.medias.map(function(media){
                    media.vm.setChecked(false);
                });
            },
            switchDeleteBtn: function(){
                var self = this;
                this.isCheckedMedia = false;
                this.medias.map(function(media){
                    if(media.vm.isChecked()){
                        self.isCheckedMedia = true;
                    }
                });
            },
            getSelectedFiles: function(){
                var self = this;
                var selectedFiles = [];
                this.medias.map(function(media){
                    if(media.vm.isChecked()){
                        selectedFiles.push(media);
                    }
                });
                return selectedFiles;
            },
            deleteFilesPopup: function(){
                this.mediaManager.modalOptions = {
                    title: 'Delete Files',
                    description: 'Confirm to delete ' + this.getSelectedFiles().length + ' file(s)?',
                    ok: this.deleteFiles,
                    cancel: function(){}
                }
                this.mediaManager.$refs.modal.showModal();
            },
            deleteFiles: function(){
                var self = this;
                this.checkedAll = false;
                var selectedFiles = this.getSelectedFiles();
                var ids = _.map(selectedFiles, 'id');
                var params = new URLSearchParams();
                params.append('id', ids);
                axios.post(config.media_library.endpoints.delete_media, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            self.medias = _.reject(self.medias, function(media) {
                                return _.indexOf(ids, media.id) >= 0;
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
            moveFilesPopup: function(){
                this.folders = _.cloneDeep(this.mediaManager.$refs.tree.data, true);
                this.folders[0].disabled = true;
                this.modalOptions = {
                    title: 'Move Files',
                    description: 'Move file(s) to:',
                    okDisabled: true,
                    ok: this.moveFiles,
                    cancel: function(){}
                }
                
                this.$refs.moveFilesModal.showModal();
            },
            moveFiles: function(){
                var self = this;
                this.checkedAll = false;
                if(!this.selectedFolder || this.selectedFolder.id == 0){
                    return;
                }
                var folder_id = this.selectedFolder.id;
                var selectedFiles = this.getSelectedFiles();
                var ids = _.map(selectedFiles, 'id');
                var params = new URLSearchParams();
                params.append('id', ids);
                params.append('folder_id', folder_id);
                axios.post(config.media_library.endpoints.move_media, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            self.medias = _.reject(self.medias, function(media) {
                                return _.indexOf(ids, media.id) >= 0;
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
            getMediasInFolder: function(){
                var self = this;
                var selectedFolder = this.mediaManager.getSelectedFolder();
                axios.get(config.media_library.endpoints.medias_in_folder + '/' + selectedFolder.id)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            self.medias = data.medias;
                            $('.media-list').scrollTop(0);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
        }
    }
</script>

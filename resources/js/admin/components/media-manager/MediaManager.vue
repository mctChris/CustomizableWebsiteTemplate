<template>
    <div :class="'media-manager' + (isSingle ? ' is-single' : '' ) + ((this.showDropzone && !this.selectedRootFolder) ? ' opened-dropzone' : '')">
        <modal ref="modal" :options="modalOptions"></modal>
        <div class="media-manager-inner justify-content-center">
            <div class="card card-default">
                <div class="card-header clearfix">
                    <div class="float-left">Media Manager</div>
                    <a href="javascript:void(0);" class="float-right" @click="close">
                        <i class="fa fa-close"></i>
                    </a>
                </div>
                <div class="media-manager-body card-body">
                    <div class="media-manager-body-top clearfix">
                        <ul class="action-list">
                            <li><button class="btn btn-sm btn-primary" :disabled="selectedRootFolder" @click="switchDropzone">Upload</button></li>
                            <li><button class="btn btn-sm btn-primary" @click="addFolderPopup">Add Folder</button></li>
                            <li><button class="btn btn-sm btn-primary" :disabled="selectedRootFolder" @click="renameFolderPopup">Rename Folder</button></li>
                            <li><button class="btn btn-sm btn-danger" :disabled="selectedRootFolder" @click="deleteFolderPopup">Delete Folder</button></li>
                        </ul>
                    </div>

                    <div class="upload-file-container clearfix">
                        <div class="float-right" v-if="watermark">
                            <label for="input-watermark">
                                <input id="input-watermark" type="checkbox" name="watermark" v-model="addWatermark">
                                Add Watermark
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <vue-dropzone ref="dropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-sending="dropzoneSending" @vdropzone-success="dropzoneUploadSuccess" @vdropzone-drop="dropzoneDrop"></vue-dropzone>
                    </div>

                    <div class="media-manager-conten-container">
                        <multipane class="media-manager-content clearfix">
                            <div class="tree-col">
                                <v-jstree ref="tree" :data="folders" text-field-name="name" draggable @item-click="folderClick" @item-drop="moveFolder" @item-drop-before="moveFolderBefore"></v-jstree>
                            </div>
                            <multipane-resizer></multipane-resizer>
                            <div class="mediaList-col">
                                <!-- <vue-dropzone ref="dropzone2" id="dropzone2" :options="dropzoneOptions" @vdropzone-sending="dropzoneSending" @vdropzone-success="dropzoneUploadSuccess" :useCustomSlot="true"> -->
                                    <media-list ref="mediaList"></media-list>
                                <!-- </vue-dropzone> -->
                            </div>
                        </multipane>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <ul class="float-left">
                        <!-- <li><a href="">Media Library</a></li> -->
                    </ul>
                    <div class="manager-footer-right float-right" v-if="caller && !isSingle">
                        <div class="d-inline-block mr-2">Selected {{ selectedFiles.length }} File(s) </div>
                        <a href="javascript:void(0);" class="btn btn-primary" @click="selectFilesToGallery">
                            Select
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import helper from './config/helper';
    export default {
        components: {
            'media-list': require('./src/MediaList').default,
        },
        mixins: [helper],
        props: ["allowBulkSelect", "watermark"],

        data: function() {
           return {
                folders: [{
                    name: "Media Library",
                    id: 0,
                    opened: true,
                    is_root: true,
                    icon: config.media_library.dir.icon,
                    children: config.media_library.dir.list
                }],
                dropzoneOptions: {
                    url: config.media_library.endpoints.upload,
                    // thumbnailWidth: 90,
                    // thumbnailHeight: 90,
                    headers: { "X-CSRF-TOKEN": config.csrf_token },
                    dictDefaultMessage: "<i class='fa fa-cloud-upload'></i> Drop files here to upload",
                    acceptedFiles: "image/*,application/*,video/*,audio/*,text/*",
                    addRemoveLinks: true,
                    // autoProcessQueue: false
                },
                showDropzone: false,
                modalOptions: {
                    textInputModel: '',
                    ok: function(){},
                    cancel: function(){}
                },
                selectedRootFolder: true,
                isSingle: false,
                selectedFiles: [],
                caller: null,
                addWatermark: true,
                initFolderSelected: false
           }
        },
        computed: {
            mediaList: function () {
                return this.$refs.mediaList;
            },
            medias: function () {
                return this.mediaList.medias;
            },
            newFolderName: function(){
                return this.modalOptions.textInputModel;
            },
        },
        created: function(){
            // this.isSingle = this.$parent.mediaManagerIsSingle;
        },
        mounted: function(){
        },
        methods: {
            switchDropzone: function() {
                this.showDropzone = !this.showDropzone;
            },
            folderClick: function(node) {
                if(this.getSelectedFolder().id == node.model.id){
                    return;
                }
                this.mediaList.unSelectAll();
                this.selectedFolderNode = node;
                this.selectedFolder = node.model;
                this.selectedFolder.opened = true;
                this.selectedRootFolder = this.getSelectedFolder().is_root;
                this.mediaList.getMediasInFolder();
            },
            getSelectedFolder: function(){
                if(!this.selectedFolder){
                    this.selectedFolder = this.$refs.tree.data[0];
                }
                return this.selectedFolder;
            },
            dropzoneDrop: function(event){
                // var self = this;
                // setTimeout(function(){
                //     var queuedFiles = self.$refs.dropzone.getQueuedFiles();
                //     queuedFiles = queuedFiles.reverse();
                //     // console.log(queuedFiles)
                //     for (var i = 0; i < queuedFiles.length; i++) {
                //         setTimeout(function (queuedFile) {
                //             self.$refs.dropzone.dropzone.processFile(queuedFile)
                //         }, i * 200, queuedFiles[i])
                //     }

                //     // console.log(queuedFiles);
                // }, 100)

            },
            dropzoneSending: function(file, xhr, formData){
                this.updatingFolder = this.getSelectedFolder();
                formData.append("watermark", this.addWatermark);
                if(file.fullPath){
                    formData.append("fullPath", file.fullPath);
                }
                formData.append('folder_id', this.getSelectedFolder().id);
                formData.append("id", config.id);
            },
            dropzoneUploadSuccess: function(file, response){
                var self = this;
                if(response.status == 'success'){
                    this.$refs.dropzone.removeFile(file);
                    if(response.uploaded_file.folder_id == this.updatingFolder.id){
                        this.medias.unshift(response.uploaded_file);
                        if(!this.isSingle){
                            setTimeout(function(){
                                self.medias[0].vm.setChecked(true)
                            })
                        }
                    }
                    this.updatingFolder.children = response.folder_tree;
                    this.$refs.tree.initializeData( this.updatingFolder.children )
                    this.updatingFolder.opened = true;
                }
            },
            addFolderPopup: function(){

                this.modalOptions = {
                    title: 'Add Folder',
                    description: 'Enter the folder name:',
                    textInput: true,
                    textInputModel: '',
                    ok: this.addFolder,
                    cancel: function(){}
                }
                this.$refs.modal.showModal();
            },
            renameFolderPopup: function(){
                this.modalOptions = {
                    title: 'Rename Folder',
                    description: 'Enter the new folder name:',
                    textInput: true,
                    textInputModel: this.getSelectedFolder().name,
                    ok: this.renameFolder,
                    cancel: function(){}
                }
                this.$refs.modal.showModal();
            },
            deleteFolderPopup: function(){
                this.modalOptions = {
                    title: 'Delete Folder',
                    description: 'Confirm to delete folder \'' + this.getSelectedFolder().name + '\' ?',
                    ok: this.deleteFolder,
                    cancel: function(){}
                }
                this.$refs.modal.showModal();
            },
            addFolder: function(){
                var self = this;
                this.updatingFolder = this.getSelectedFolder();
                var params = new URLSearchParams();
                params.append('parent_folder_id', this.selectedFolder.id);
                params.append('new_folder_name', this.newFolderName);
                
                axios.post(config.media_library.endpoints.create_folder, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            self.modalOptions.textInputModel = '';
                            if(data.folder.is_new){
                                self.updatingFolder.addChild({
                                    id: data.folder.id,
                                    name: data.folder.name,
                                    icon: config.media_library.dir.icon,
                                })
                                setTimeout(function(){
                                    self.selectFolder(data.folder.id, false);
                                }, 10)

                            }else{
                                alert('Folder "' + data.folder.name + '" already exists');
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
            renameFolder: function(){
                var self = this;
                this.updatingFolder = this.getSelectedFolder();
                var params = new URLSearchParams();
                params.append('folder_id', this.selectedFolder.id);
                params.append('new_folder_name', this.newFolderName);
                
                axios.post(config.media_library.endpoints.rename_folder, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            self.modalOptions.textInputModel = '';
                            self.updatingFolder.name = data.folder.name;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
            moveFolderBefore: function(node, item, draggedItem, e){
                this.movingFolder = draggedItem;
            },
            moveFolder: function(node, item, e){
                var self = this;
                var params = new URLSearchParams();
                params.append('folder_id', this.movingFolder.id);
                params.append('destination_folder_id', item.id);
                
                axios.post(config.media_library.endpoints.move_folder, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            self.movingFolder.name = data.folder.name;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        self.movingFolder = null;
                    }
                );
                
            },
            deleteFolder: function(){
                var self = this;
                this.updatingFolderNode = this.selectedFolderNode;
   
                var params = new URLSearchParams();
                params.append('folder_id', this.selectedFolder.id);
                
                axios.post(config.media_library.endpoints.delete_folder, params)
                    .then(function (response) {
                        var data = response.data;
                        if(data.status == 'success'){
                            var index = self.updatingFolderNode.parentItem.indexOf(self.updatingFolderNode.model);
                            self.updatingFolderNode.parentItem.splice(index, 1);
                            self.selectFolder(self.updatingFolderNode.$parent.model.id);
                        }else{
                            if(data.message){
                                alert(data.message)
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {

                    }
                );
            },
            open: function(caller, isSingle = false, lastGallery = false, folderId = false){
                var self = this;
                this.caller = caller;
                this.isSingle = isSingle;
                this.mediaList.unSelectAll();
                this.$parent.mediaManagerShown = true;
                if(this.initFolderSelected){
                    return;
                }
                if(lastGallery || folderId){
                    this.initFolderSelected = true;
                    if(lastGallery){
                        folderId = lastGallery.folder_id;
                    }
                    this.selectFolder(folderId, true)
                }
            },
            selectFolder: function(folder_id, openParentFolders){
                var folders = this.$refs.tree.$children;
                this.getFolderById(folders, folder_id);
                if(this.targetFolder){
                    this.unSelectAllFolders(folders);
                    this.folderClick(this.targetFolder);
                    this.targetFolder.model.selected = true;

                    if(openParentFolders){
                        var parent = this.targetFolder.$parent;

                        while(parent){
                            if(!parent.model || parent.model.id == 0){
                                break;
                            }
                            parent.model.openChildren();
                            parent = parent.$parent;
                        }
                    }
                }
            },
            unSelectAllFolders: function(folders){
                for(var i = 0; i < folders.length; i++){
                    folders[i].model.selected = false;
                    this.unSelectAllFolders(folders[i].$children);
                }
            },
            getFolderById: function(folders, targetFolderId){
                if(!folders){
                    return;
                }
                for(var i = 0; i < folders.length; i++){
                    if(folders[i].model.id == targetFolderId){
                        this.targetFolder = folders[i];
                    }

                    this.getFolderById(folders[i].$children, targetFolderId);
                }

            },
            close: function() {
                this.$parent.mediaManagerShown = false;
            },
            selectFilesToGallery: function(){
                if(typeof this.caller == 'function'){
                    var media = this.mediaList.getSelectedFiles()[0];
                    this.caller(media.path, {title: media.title});
                }else{
                    if(this.isSingle){
                        this.caller.mediasData = this.mediaList.getSelectedFiles()
                    }else{
                        this.caller.mediasData =  _.unionBy(this.caller.mediasData, this.mediaList.getSelectedFiles(), 'id');
                    }
                }
                this.close();
            }
        }
    }
</script>

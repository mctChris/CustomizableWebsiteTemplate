
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import BootstrapVue from 'bootstrap-vue';
import VJstree from 'vue-jstree';
import vue2Dropzone from 'vue2-dropzone';
import draggable from 'vuedraggable';
import VueCropper from 'vue-cropper';
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import moment from 'moment';
import { Multipane, MultipaneResizer } from 'vue-multipane';
import dot from 'vue-text-dot';
import { Sketch } from 'vue-color';
import Photoswipe from 'vue-pswipe';
import VueTimepicker from 'vue2-timepicker';
import 'vue2-timepicker/dist/VueTimepicker.css';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import { ModelSelect } from 'vue-search-select';
import 'vue-search-select/dist/VueSearchSelect.css';
import FunctionalCalendar from 'vue-functional-calendar';
import VueAutosuggest from "vue-autosuggest";


window.axios = require('axios');
window._ = require('lodash');
window.moment = moment;
// import vue2Dropzone from './node_modules/vue2-dropzone/src/index.vue';


Vue.use(BootstrapVue);
Vue.use(VJstree);
Vue.use(VueCropper);
Vue.use(datePicker);
Vue.use(Photoswipe);
Vue.use(FunctionalCalendar, {
    dayNames: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']
});
Vue.use(VueAutosuggest);


Vue.component('editor', require('@tinymce/tinymce-vue').default);
Vue.component('draggable', draggable);
Vue.component('vue-dropzone', vue2Dropzone);
Vue.component('modal', require('./admin/components/media-manager/src/Modal.vue').default);
Vue.component('media-manager', require('./admin/components/media-manager/MediaManager.vue').default);
Vue.component('gallery-list', require('./admin/components/media-manager/src/GalleryList.vue').default);
Vue.component('multipane', Multipane);
Vue.component('multipane-resizer', MultipaneResizer);
Vue.component('dot', dot);
Vue.component('sketch-picker', Sketch);
Vue.component('vue-timepicker', VueTimepicker);
Vue.component('multiselect', Multiselect);
Vue.component('searchselect', ModelSelect);
// Vue.component('autocomplete', Autocomplete);

if(record){
    var recordStatus = {
        data: {
            status: {
                selected: record.is_show,
                options: [
                    { text: 'Online', value: '1' },
                    { text: 'Offline', value: '0' },
                ],
                datepicker: {
                    date: null,
                    options: {
                        format: 'YYYY-MM-DD HH:mm',
                        useCurrent: false,
                        showClear: true,
                        showClose: true,
                    }
                }
            },
            datepickerOptions: {
                format: 'YYYY-MM-DD',
                useCurrent: true,
                showClear: true,
                showClose: true,
            }
        }
    }
    mixins.push(recordStatus);
}



var editorParams = {
    computed: {
        editorParams: function(){
            var self = this;
            return {
                content_css: [
                    // 'assets/frontend/common/css/normalize.css',
                    // 'assets/frontend/common/css/components.css',
                    // 'assets/frontend/common/css/webflow.css',
                    'assets/frontend/common/css/website.css',
                ],
                plugins: [
                  "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                  "searchreplace wordcount visualblocks visualchars code fullscreen",
                  "insertdatetime media nonbreaking save table directionality",
                  "emoticons template paste"
                ],
                toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "print preview media | forecolor backcolor emoticons fontselect fontsizeselect charmap",
                image_advtab: true,
                image_title: true,
                image_alt: true,
                image_description: false,
                height : 350,
                relative_urls: true,
                document_base_url: this.config.base_url,
                fontsize_formats: "8px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 24px 26px 28px 30px 32px 34px 36px 40px",
                color_map: [
                    "007883", "",
                    "ec008c", "",
                    "29abe2", "",
                    "ca63ff", "",
                    "272727", "",
                    "71d2d0", "",
                    "ffcb6e", "",
                    "004ebc", "",
                    "e72ada", "",
                    "ec1d24", "",
                    "ef6d00", "",
                    "68b6ce", "",
                    "000000", "",
                    "ffffff", "",
                ],
                templates: [

                ],
                file_picker_types: 'image',
                file_picker_callback: function (cb, value, meta) {
                    self.openMediaManager(cb, true);
                },
                init_instance_callback: function(){
                    tinyMCE.triggerSave();
                    window.initialMainFormState = getMainFormSerialize();
                }
            }
        }
    }
}

mixins.push(editorParams);

window.app = new Vue({
    el: '#app',
    mixins: mixins,
    data: {
        record: record,
        records: records,
        config: config,
        mediaManagerShown: false,
        mediaManagerIsSingle: false,
        r_index: -1,
        selected_bulk_action: ''
    },
    methods: {
        openMediaManager: function(caller, isSingle = false, lastGallery = false, folderId = false){
            this.$refs.manager.open(caller, isSingle, lastGallery, folderId);
        },
        currentDateTime: function(){
            return moment().format('YYYY-MM-DD HH:mm');
        },
        JsonStringify: function(string){
            return JSON.stringify(string);
        },
        changeBulkAction: function(e){
            this.selected_bulk_action = e.target.value;
        },
        submitBulkAction: function(){
            document.getElementById('deleteForm').submit();
        },
        stringLength: function(str) {
            var s = str.length;
            for (var i=str.length-1; i>=0; i--) {
                var code = str.charCodeAt(i);
                if (code > 0x7f && code <= 0x7ff) s++;
                else if (code > 0x7ff && code <= 0xffff) s+=1;
                if (code >= 0xDC00 && code <= 0xDFFF) i--; //trail surrogate
            }
            return s;
        }
    }
});

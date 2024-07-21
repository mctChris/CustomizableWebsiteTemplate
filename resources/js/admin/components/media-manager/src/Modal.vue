<template>
    <div>
        <b-modal ref="myModalRef" :title="options.title" centered @ok="options.ok" @cancel="options.cancel" :ok-disabled="options.okDisabled || false">
            <div class="d-block">
                <p>{{ options.description }}</p>
                <input v-if="options.textInput" ref="input" type="text" class="form-control" @keyup.enter="handleEnter" v-model="options.textInputModel">
                <slot></slot>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: ['options'],
        data: function(){
            return {

            }
        },
        methods: {
            showModal: function() {
                var self = this;
                this.$refs.myModalRef.show();
                setTimeout(function(){
                    if(self.$refs.input){
                        self.$refs.input.select();
                    }
                })
            },
            hideModal: function() {
                this.$refs.myModalRef.hide();
            },
            handleEnter: function(){
                this.options.ok();
                this.hideModal();
            } 
        }
    }
</script>

<div class="form-group row mt-5">
    <label class="col-md-2 col-form-label" for="text-input">Status</label>
    <div class="col-md-10">
        <div class="mb-3">
            <b-form-radio-group
            id="status"
            ref="status"
            buttons
            button-variant="outline-primary"
            v-model="status.selected"
            :options="status.options"
            name="is_show" />
        </div>
        <div v-if="status.selected == 1" class="">
            <div></div>
            <table class="common-tb">
                <tr>
                    <th>Publish at</th>
                    <td>
                        <div class="position-relative">
                            <date-picker class="d-inline-block" :value="record.publish_at || currentDateTime()" name="publish_at" :config="status.datepicker.options" autocomplete="off"></date-picker>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Offline at</th>
                    <td>
                        <div class="position-relative">
                            <date-picker class="d-inline-block" :value="record.offline_at" name="offline_at" :config="status.datepicker.options" autocomplete="off"></date-picker>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
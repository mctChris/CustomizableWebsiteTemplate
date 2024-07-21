@php
   
@endphp
<functional-calendar ref="{{ $name }}Calendar" :is-multiple-date-picker='true' :date-format="'yyyy-mm-dd'" :sunday-start="true" v-model="{{ $name }}_selected" class="d-inline-block" style="vertical-align: top;width: 300px;"></functional-calendar>
<input type="hidden" name="{{ $name }}" value="">
<input type="hidden" v-for="formattedDay in {{ $name }}_formattedDays" name="{{ $name }}[]" :value="formattedDay">
<div class="d-inline-block ml-5">
    <div class="mb-1"><b>Selected Dates: </b></div>
    <div v-if="{{ $name }}_selected.selectedDates.length == 0">No Date Selected</div>
    <ul style="padding-left: 20px;">
        <li class="" v-for="date in {{ $name }}_formattedDays" v-text="date"></li>
    </ul>
</div>
@section('js-before')
    @parent
    <script>
        mixins.push({
            data: {
                {{ $name }}_selected: {
                    selectedDates: [],
                },
            },
            computed: {
                {{ $name }}_formattedDays: function(){
                    return _.transform(this.{{ $name }}_selected.selectedDates, function(result, item) {
                      result.push(moment(item.date, 'YYYY-M-D').format('YYYY-MM-DD'));
                      return true;
                    }, []).sort();
                },
            },
            mounted: function(){
                var self = this;
                this.$nextTick(function(){
                    self.$refs.{{ $name }}Calendar.$emit('input', self.$refs.{{ $name }}Calendar.calendar);

                    for(var i = 0; i < self.record.{{ $name }}.length; i++){
                        var date = self.record.{{ $name }}[i];

                        date = moment(date, 'YYYY-MM-DD').format('YYYY-M-D');

                        if(i == 0){
                            self.$refs.{{ $name }}Calendar.ChooseDate(date);
                        }

                        self.{{ $name }}_selected.selectedDates.push({
                            date: date
                        })
                    }
                    self.$refs.{{ $name }}Calendar.markChooseDays();

                    $('.vfc-today').removeClass('vfc-today');
                })
            }
        })
    </script>
@endsection
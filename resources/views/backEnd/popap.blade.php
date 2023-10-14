@extends('backEnd.master')
@section('mainContent')
    {{--            //popap --}}
    <div  id="modalLoginFormaa"  data-toggle="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <form class="modal-dialog" role="document" action="{{route('tokos')}}" method="post">
            @csrf
            <div class="modal-content" style="width: 700px">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Скидка</h4>
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
                </div>
                <div >
                    {{--                <div class="">--}}
                    <div class="row mt-5 " >

                        <div class="col-lg-12">
                            <div class="primary_input mb-15" >
                                <div class="primary_input_field ">
                                    <div class="ml-3 mt-1">
                                        <input name="ka" class=" form-check-input" @if(Modules\Appearance\Entities\Header::find(1)->ka == 'true') checked @endif type="checkbox" value="" id="flexCheckCheckedDisabled" >
                                        <label  class="primary_input_label form-check-label" for="flexCheckCheckedDisabled">
                                            Checkbox
                                        </label>
                                    </div>

                                </div>

                                <label class="primary_input_label" for=""> {{ __('product.discount') }}
                                </label>
                                <input class="primary_input_field tokos" name="tokos" id="discount"
                                       placeholder="{{ __('product.discount') }}" type="number" min="0"
                                       step="{{step_decimal()}}" value="@if($a = Modules\Appearance\Entities\Header::find(1)->tokos){{$a}}@endif">
                                <span class="text-danger" id="error_discunt">{{ $errors->first('discount')
                                                }}</span>

                                <label class="primary_input_label" for=""> Link           </label>
                                <input class="primary_input_field link" name="link" id="link"
                                       placeholder="Link" type="text" min="0"
                                       step="{{step_decimal()}}" value="@if($a = Modules\Appearance\Entities\Header::find(1)->link){{$a}}@endif">
                                <span class="text-danger" id="error_discunt">{{ $errors->first('discount')
                                                }}</span>
                            </div>
                        </div>
 </div>





                    <div class="row">

                        <div class="col-lg-12">
                            <div class="primary_input mb-15">
                                <label class="primary_input_label" for="">Discount date </label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">

                                                <input type="datetime-local" value="@if($a = Modules\Appearance\Entities\Header::find(1)->time){{$a}}@endif" id="time" name="time" class=" primary-input form-control">
                                            </div>
                                        </div>

                                        <button class="" type="button">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <span class="text-danger" id="error_date"></span>
                            </div>
                        </div>
                    </div>




                    <div class="form-check">
                        <div class="" style="width: 100%">
                            <label class="form-control-label" for="validationCustom01123descrep">Text</label>
                            <textarea name="text" class="text"  id="validationCustom01123descrep" rows="10" cols="80" >
                            @if($a = Modules\Appearance\Entities\Header::find(1)->text){{$a}}@endif
                            </textarea>

                            <div class="valid-feedback">
                                Ճիշտ է
                            </div>
                            <div class="invalid-feedback">
                                Լրացրեք դաշտը
                            </div>
                            @error('description')
                            <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <script>

                        CKEDITOR.replace( 'validationCustom01123descrep' );
                    </script>
                    {{--                </div>--}}
                </div>
                <div class="modal-footer  clickdiv d-flex justify-content-center">
                    <button type="submit" class="btn btn-default  sales" data-dismiss="modal" aria-label="Close">Сохранять</button>
                </div>
            </div>
        </form>


        <script>
            $('#flexCheckCheckedDisabled').change(function (){
                $.post( "{{route('change_tokos')}}", {_token:'{{csrf_token()}}',ka:$(this).is(":checked")})
                // .done(function() {
                //     alert( "second success" );
                // })
                // .fail(function() {
                //     alert( "error" );
                // })
            })
            $(".clickdiv").click(function(el) {
                console.log($('.text').val())
                $.post( "{{route('tokos')}}", {
                    _token:'{{csrf_token()}}',
                    tokos:$('.tokos').val(),
                    text:$('#validationCustom01123descrep').val(),
                    time:$('#time').val(),
                    link:$('#link').val(),
                })
                // .done(function() {
                //     window.location.pathname = '/admin-dashboard'
                // })
                // .fail(function() {
                //     alert( "error" );
                // })
            });
        </script>


    </div>
    {{--            popap end--}}
@endsection

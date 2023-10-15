@extends('backEnd.master')
@section('mainContent')
@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="alert alert-success alert_abg" style="display: none">
    <strong>Success!</strong> Send.
</div>

<textarea name="text" class="text"  id="validationCustom01123descrep" rows="10" cols="200" ></textarea>

<div style="text-align: center"><!--[if mso]>
    <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:40px;v-text-anchor:middle;width:200px;" stroke="f" fillcolor="#d71919">
        <w:anchorlock/>
        <center>
    <![endif]-->
    <div onclick="send()"
            style="background-color:#d71919;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Send
    </div>
    <!--[if mso]>
    </center>
    </v:rect>
    <![endif]--></div>
<script>
    function send(){
        data = {
            text : CKEDITOR.instances.validationCustom01123descrep.getData()
            // text : $('.text').val()
        }
        console.log(data)
        $.post('/parol/reset/1',data).then(function (){
            $('.alert_abg').css('display','block')
        })
    }
</script>

    @include('backEnd.partials._deleteModalForAjax',['item_name' => __('marketing.subscriber')])

    <section class="admin-visitor-area up_st_admin_visitor">


        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('marketing.subscriber_list') }}</h3>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table">
                            <div class="" id="item_table">
                                @include('marketing::subscribers.components.list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
<script>

    CKEDITOR.replace( 'validationCustom01123descrep' );
</script>
@endsection

@include('marketing::subscribers.components._scripts')

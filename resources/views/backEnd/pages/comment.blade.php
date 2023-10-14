@extends('backEnd.master')
@section('styles')

    <link rel="stylesheet" href="{{asset(asset_path('modules/review/css/style.css'))}}" />


@endsection
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">

        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-lg-12">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active show" id="all_review">
                            <div class="col-12">
                                <div class="box_header common_table_header">
                                    <div class="main-title d-md-flex">
                                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">Comment</h3>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="QA_section QA_section_heading_custom check_box_table">
                                    <div class="QA_table">
                                        <div class="" id="all_item_table">

                                            <div class="">
                                                {{\App\Models\Comment::find(request()->id)->text}}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection




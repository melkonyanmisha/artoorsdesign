@extends('frontend.default.layouts.newApp')

@section('content')
    <main>
        @include('frontend.default.includes.mainInclude')
        <section class="wrapper">
            {{--            <div class="d_flex from_to">--}}
            {{--                <a class="from_this" href="{{url('/')}}">Home</a>--}}
            {{--                <span class="slashes">/</span>--}}
            {{--                <span class="this_page">My Profile</span>--}}
            {{--            </div>--}}
            @section('breadcrumb')
                My Profile
            @endsection
            @include('frontend.default.partials._breadcrumb')
            <div class="d_flex my_profile_section">
                <div class="profile_menu">
                    <span class="prof_menu_sp prof_menu_sp_active edit_profile">Edit My Profile</span>
                    <span class="prof_menu_sp my_purchases">My Purchases</span>
                    <span class="prof_menu_sp my_comments">My Comments</span>
                    <span class="prof_menu_sp my_favorites">My Favorites</span>
                    <span class="prof_menu_sp settings_">Settings</span>
                </div>

                @include('frontend.default.new.profile.pagination')
                @include('frontend.default.new.profile.edit')
                @include('frontend.default.new.profile.purchases')
                @include('frontend.default.new.profile.comments')
                @include('frontend.default.new.profile.favorites')
                @include('frontend.default.new.profile.settings')
            </div>
        </section>
    </main>
@endsection
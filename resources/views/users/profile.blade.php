@extends('layouts.app')
@section('title', 'User Profile')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            User Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User Profile</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header">
                            <div class="widget-user-image">
                                <img class="img-circle" src="/images/users/default_user.jpg" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-capitalize">&emsp;{{ $loggedUser->name }}'s profile</h3>
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <form action="{{ route('user.profile.action') }}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <hr>
                                        <h4 class="text-info">&emsp;&emsp;User Info</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="username" class="col-md-3 control-label"><i class="text-red">* </i>User Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="username" placeholder="User Name" value="{{ !empty(old('username'))? old('username') : $loggedUser->username }}" tabindex="1">
                                                @if(!empty($errors->first('username')))
                                                    <p style="color: red;" >{{$errors->first('username')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label"><i class="text-red">* </i>Name : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ !empty(old('name'))? old('name') : $loggedUser->name }}" tabindex="2" >
                                                @if(!empty($errors->first('name')))
                                                    <p style="color: red;" >{{$errors->first('name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="col-md-3 control-label"><i class="text-red">* </i>Email : </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="email" placeholder="User email" value="{{ !empty(old('email')) ? old('email') : $loggedUser->email }}" tabindex="3">
                                                @if(!empty($errors->first('email')))
                                                    <p style="color: red;" >{{$errors->first('email')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h4 class="text-warning">&emsp;&emsp;Change Password</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="password" class="col-md-3 control-label"> New Password : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('password')) ? 'has-error' : '' }}">
                                                <input type="password" name="password" class="form-control" placeholder="New password"  tabindex="4">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{$errors->first('password')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-md-3 control-label"> Confirm New Password : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('password')) ? 'has-error' : '' }}">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" tabindex="5">
                                                @if(!empty($errors->first('password')))
                                                    <p style="color: red;" >{{ $errors->first('password') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h4 class="text-red">&emsp;&emsp;Authentication</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="currentPassword" class="col-md-3 control-label"><b style="color: red;">* </b> Current Password : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('currentPassword')) ? 'has-error' : '' }}">
                                                <input type="password" name="currentPassword" class="form-control" placeholder="Current password"  tabindex="6">
                                                @if(!empty($errors->first('currentPassword')))
                                                    <p style="color: red;" >{{$errors->first('currentPassword')}}</p>
                                                @endif
                                            </div>
                                        </div><br>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-warning btn-block btn-flat update_button" tabindex="7">Update</button>
                                    </div>
                                </div><br>
                            </div>
                        </form >
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
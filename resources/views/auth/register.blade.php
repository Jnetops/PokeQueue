@extends('layouts.app')
<link href="{{ asset('css/register.css') }}" rel="stylesheet">
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading register text-center">Account Creation</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('trainer_name') ? ' has-error' : '' }}">
                            <label for="trainer_name" class="col-md-4 control-label">Display Name</label>

                            <div class="col-md-6">
                                <input id="trainer_name" type="text" min="4" max="15" class="form-control" name="trainer_name" required>

                                @if ($errors->has('trainer_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('trainer_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('trainer_level') ? ' has-error' : '' }}">
                            <label for="trainer_level" class="col-md-4 control-label">Trainer Level</label>

                            <div class="col-md-6 select-padding">

                                <select name="trainer_level" class="select">
                                  <option value=""></option>
                                  <option value="40">40</option>
                                  <option value="39">39</option>
                                  <option value="38">38</option>
                                  <option value="37">37</option>
                                  <option value="36">36</option>
                                  <option value="35">35</option>
                                  <option value="34">34</option>
                                  <option value="33">33</option>
                                  <option value="32">32</option>
                                  <option value="31">31</option>
                                  <option value="30">30</option>
                                  <option value="29">29</option>
                                  <option value="28">28</option>
                                  <option value="27">27</option>
                                  <option value="26">26</option>
                                  <option value="25">25</option>
                                  <option value="24">24</option>
                                  <option value="23">23</option>
                                  <option value="22">22</option>
                                  <option value="21">21</option>
                                  <option value="20">20</option>
                                  <option value="19">19</option>
                                  <option value="18">18</option>
                                  <option value="17">17</option>
                                  <option value="16">16</option>
                                  <option value="15">15</option>
                                  <option value="14">14</option>
                                  <option value="13">13</option>
                                  <option value="12">12</option>
                                  <option value="11">11</option>
                                  <option value="10">10</option>
                                  <option value="9">9</option>
                                  <option value="8">8</option>
                                  <option value="7">7</option>
                                  <option value="6">6</option>
                                  <option value="5">5</option>
                                  <option value="4">4</option>
                                  <option value="3">3</option>
                                  <option value="2">2</option>
                                  <option value="1">1</option>
                                </select>
                                @if ($errors->has('trainer_level'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('trainer_level') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('trainer_team') ? ' has-error' : '' }}">
                            <label for="trainer_team" class="col-md-4 control-label">Trainer Team</label>

                            <div class="col-md-6 select-padding">

                                <select name="trainer_team" class="select">
                                  <option value=""></option>
                                  <option value="1">Mystic</option>
                                  <option value="2">Valor</option>
                                  <option value="3">Instinct</option>
                                </select>

                                @if ($errors->has('trainer_team'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('trainer_team') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary register">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

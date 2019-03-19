@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route("themes.update") }}">
                        @csrf
                        <fieldset>
                            <fieldset class="form-group">
                                <legend>Select theme</legend>
                                @foreach ($themes as $theme)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="theme" value={{ $theme->id }} {{ auth()->user()->theme->id == $theme->id ? "checked" : ""}} >{{ $theme->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </fieldset>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

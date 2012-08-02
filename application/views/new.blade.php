@layout('templates.main')
@section('content')
<div class="span8">
    <h2>Creating new post</h2>
    <hr />
    {{ Form::open('admin') }}
    	{{ Form::hidden('post_author', $user->id) }}
        <!-- title field -->
        <p>{{ Form::label('post_title', 'Post Title') }}</p>
        {{ $errors->first('post_title', Alert::error(":message")) }}
        <p>{{ Form::text('post_title', Input::old('post_title')) }}</p>
        <!-- body field -->
        <p>{{ Form::label('post_body', 'Post Body') }}</p>
        {{ $errors->first('post_body', Alert::error(":message")) }}
        <p>{{ Form::textarea('post_body', Input::old('post_body')) }}</p>
        <!-- submit button -->
        <p>{{ Form::submit('Create') }}</p>
    {{ Form::close() }}
</div>
@endsection
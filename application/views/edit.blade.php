@layout('templates.main')
@section('content')
<div class="span8">
    <h2>Editing post</h2>
    <hr />
    {{ Form::open('post/'.$post->id, 'PUT') }}
    	{{ Form::hidden('post_author', $user->id) }}
        <!-- title field -->
        <p>{{ Form::label('post_title', 'Post Title') }}</p>
        {{ $errors->first('post_title', Alert::error(":message")) }}
        <p>{{ Form::text('post_title', ( Input::old('post_title') ? Input::old('post_title') : $post->post_title)) }}</p>
        <!-- body field -->
        <p>{{ Form::label('post_body', 'Post Body') }}</p>
        {{ $errors->first('post_body', Alert::error(":message")) }}
        <p>{{ Form::textarea('post_body', ( Input::old('post_body') ? Input::old('post_body') : $post->post_body)) }}</p>
        <!-- submit button -->
        <p>{{ Form::submit('Save') }}</p>
    {{ Form::close() }}
</div>
@endsection


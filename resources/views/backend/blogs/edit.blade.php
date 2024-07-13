@extends('backend.layouts.master')

@section('style')
@stop

@section('content')
    <div class="content">
        <form method="post" action="{{route('admin.blogs.update', ['id' => $item->id])}}" class="form-horizontal" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header">
                            <h4 class="card-title">Edit a blog</h4>
                        </div>
                        <div class="card-body ">
                            @csrf()
                            <div class="row pb-2">
                                @if ($errors->has('title'))
                                    <div class="text-danger col-md-12 offset-md-2">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </div>
                                @endif

                                <label class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <input type="text" name="title" class="form-control"
                                               value="{{old('title', $item->title)}}">
                                    </div>
                                </div>
                            </div>
                            @if($categories->count())
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Categories</label>
                                    <div class="col-lg-5 col-md-6 col-sm-3">
                                        <select class="selectpicker" data-style="btn btn-info btn-round" multiple
                                                title="Categories" data-size="7" name="category_ids[]">
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}" @if(in_array($category->id, $item->categories->pluck('id')->toArray())) selected @endif>{{$category->name}} {{ $category->id  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                @if ($errors->has('content'))
                                    <div class="text-danger col-md-12 offset-md-2">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </div>
                                @endif
                                <label class="col-sm-2 col-form-label">Content</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <textarea type="text" name="content" id="editor"
                                                  class="form-control">{{old('content', $item->content)}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <div class="form-group float-right">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('script')
    @include('backend.layouts.editor-script')
@stop

@if (count($elements) > 0)
    @include('partials.admin.blog.blogCategoriesTable')
@else
    <div class="alert alert-warning" role="alert">
            {{__('admin.noContentList')}}
    </div>
@endif
<div class="btn-group">
    <a class="btn btn-xs btn-default m-r" href="{{ $href }}" target="_blank"><i class="fa fa-eye"></i></a>
    <a href="{{ route('back.articles.edit', [$id]) }}" class="btn btn-xs btn-default m-r"><i class="fa fa-pencil"></i></a>
    <a href="#" class="btn btn-xs btn-danger delete" data-url="{{ route('back.articles.destroy', [$id]) }}"><i class="fa fa-times"></i></a>
</div>

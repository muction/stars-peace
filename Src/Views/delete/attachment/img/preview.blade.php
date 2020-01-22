@extends("StarsPeace::iframe")


@section("car-body")
    <div class="masonry-grid gap-2" data-provide="photoswipe">

        @foreach($files as $file)
            <a class="masonry-item" href="#">
                <img src="/storage/{{ ($file['save_file_path'].'/'.$file['save_file_name']) }}" alt="The selected child description">
            </a>
        @endforeach
    </div>
@endsection
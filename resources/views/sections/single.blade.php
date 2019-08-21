<h3 class="px-4 py-2" style="background:#efefef; border-top:solid 1px #dadada">{{ $section->name }}</h3>
@foreach($section->questions as $question)
    @include('survey::questions.single')
@endforeach
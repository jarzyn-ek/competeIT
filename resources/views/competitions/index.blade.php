@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between">
        <div>
            <form>
                @csrf
                <div class="form-group">
                    <a class="btn btn-primary" href="{{ route('competitions_create') }}" type="button"><i class="oi oi-plus"></i> Add</a>
                </div>
            </form>
        </div>
        <div>
            <form>
                <div class="form-group">
                    <input class="form-control" name="search" placeholder='{{__('Search')}}...' value="{{ request()->query('search') }}"/>
                </div>
            </form>
        </div>

    </div>
    <div>
        <table class="table">
            <tbody>
                @foreach ($competitions as $key => $competition)
                    <tr>
                        <td>
                            {{$loop->iteration + $competitions->perPage() * ($competitions->currentPage()-1)}}
                        </td>
                        <td>
                            <a href="{{ route('competitions_show', ['id'=>$competition->id]) }}">{{ $competition->name }} </a>
                        </td>
                        <td class="actions">
                            @if ($competition->user->id == auth()->user()->id)
                            <a class="btn btn-primary" href="{{ route('competitions_edit', ['competition'=>$competition])  }}" type="button"><i class="oi oi-pencil"></i> {{__('Edit')}}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $competitions->appends(request()->query())->links() }}
    </div>
</div>
@endsection

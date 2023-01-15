@php
    use Illuminate\Support\Str;

    $formSessions = [
        'add_breed.success', 'add_breed.failed',
        'update_breed.success', 'update_breed.failed',
    ];
    $formSessionMessage = NULL;

    foreach ( $formSessions as $formSession ) {
        $message = Session::get( $formSession );

        if ( $message ) {
            $formSessionMessage = $message;
            break;
        }
    }
@endphp

@extends('layouts.admin', [ 'pageTitle' => 'Manage Breeds' ])

@section('content')
    @if ($formSessionMessage)
        <div class="alert alert-dark" role="alert">
            <label class="lead">{{ $formSessionMessage }}</label>
        </div>
    @endif
    <section class="row mb-3">
        <div class="row">
            <div class="col-8">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <h5 class="card-title">List of breeds</h5>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Breed Number</th>
                                            <th scope="col">Breed</th>
                                            <th scope="col">Pigs Count</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($breeds as $breed)
                                            <tr>
                                                <th scope="row">{{ $breed->id }}</th>
                                                <td>{{ $breed->name }}</td>
                                                <td>{{ $breed->pigs->count() }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary edit--breed" data-data="{{ $breed }}">Edit</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" align="center">No result(s) found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-5">
                                <div class="col custom__pagination">
                                    {{ $breeds->links( 'pagination::bootstrap-5' ) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <form action="{{ route('admin.new_breed') }}" method="post" class="{{ $errors->has('update_name') ? 'collapse' : '' }}" id="new__breed-form">
                            @csrf

                            <div class="row">
                                <div class="col-12 md-4">
                                    <h5 class="card-title">Add new breed</h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="" class="lead">Breed name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>

                        <form action="{{ route('admin.update_breed') }}" method="post" class="{{ ! $errors->has('update_name') ? 'collapse' : '' }}" id="update__breed-form">
                            @csrf

                            <div class="row">
                                <div class="col-12 md-4">
                                    <h5 class="card-title">Update breed</h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="" class="lead">Breed name</label>
                                    <input type="text" name="update_name" id="update_name" class="form-control @error('update_name') is-invalid @enderror" value="{{ old('update_name') }}" required>

                                    @error('update_name')
                                        <span class="invalid-feedback" id="update__invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <input type="hidden" name="update_id" id="update_id" value="{{ $errors->has('update_name') ? old('update_id') : '' }}">
                                    <button type="button" class="btn btn-secondary" id="edit--cancel">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const editBreedButton = document.querySelectorAll( '.edit--breed' )
            if ( editBreedButton.length > 0 ) {
                editBreedButton.forEach( el => {
                    el.addEventListener('click', () => {
                        let data = el.getAttribute( 'data-data' )
                        if ( data ) {
                            data = JSON.parse( data )

                            document.getElementById( 'update_id' ).value = data.id
                            document.getElementById( 'update_name' ).value = data.name
                            document.getElementById( 'update__breed-form' ).classList.remove( 'collapse' )
                            document.getElementById( 'new__breed-form' ).classList.add( 'collapse' )
                        }
                    })
                } )
            }

            const editCancel = document.getElementById( 'edit--cancel' )
            if ( editCancel ) {
                editCancel.addEventListener('click', () => {
                    document.getElementById( 'update__breed-form' ).reset()
                    document.getElementById( 'update__breed-form' ).classList.add( 'collapse' )
                    document.getElementById( 'new__breed-form' ).classList.remove( 'collapse' )

                    document.getElementById( 'update_name' ).classList.remove( 'is-invalid' )
                    document.getElementById( 'update__invalid-feedback' ).remove()
                })
            }
        });
    </script>
@endsection
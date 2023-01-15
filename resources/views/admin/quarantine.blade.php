@php
    use Illuminate\Support\Str;

    $formSessions = [
        'restore_pig.success', 'restore_pig.failed',
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

@extends('layouts.admin', [ 'pageTitle' => 'Manage Quarantine' ])

@section('content')
    @if ($formSessionMessage)
        <div class="alert alert-dark" role="alert">
            <label class="lead">{{ $formSessionMessage }}</label>
        </div>
    @endif
    <section class="row mb-3">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <h5 class="card-title">List of quarantined pigs</h5>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Quarantine Number</th>
                                            <th scope="col">Pig Number</th>
                                            <th scope="col">Reason</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($quarantines as $quarantine)
                                            <tr>
                                                <th scope="row">{{ $quarantine->id }}</th>
                                                <td>{{ $quarantine->pig->pig_no }}</td>
                                                <td>{{ $quarantine->pig->notes }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary remove--quarantine" data-data="{{ $quarantine }}">Remove</button>
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
                                    {{ $quarantines->links( 'pagination::bootstrap-5' ) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form id="restore--pig" action="{{ route('admin.restore_pig') }}" method="POST" class="d-none">
        @csrf

        <input type="hidden" name="restore_id" id="restore_id">
    </form>
@endsection

@section('js')
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const restoreButton = document.querySelectorAll( '.remove--quarantine' )
            if ( restoreButton.length > 0 ) {
                restoreButton.forEach( el => {
                    el.addEventListener('click', () => {
                        let data = el.getAttribute( 'data-data' )
                        if ( data ) {
                            data = JSON.parse( data )

                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure?',
                                text: 'Pig will be removed from the Quarantine List!',
                                showCancelButton: true,
                                showConfirmButton: true,
                                cancelButtonText: 'Cancel',
                                confirmButtonText: 'Confirm'
                            }).then( (e) => {
                                if ( e.isConfirmed ) {
                                    document.getElementById( 'restore_id' ).value = data.id
                                    document.getElementById( 'restore--pig' ).submit()
                                }
                            } )
                        }
                    })
                } )
            }
        });
    </script>
@endsection
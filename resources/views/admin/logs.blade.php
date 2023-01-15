@extends('layouts.admin', [ 'pageTitle' => 'System Logs' ])

@section('content')
    <section class="row mb-3">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <h5 class="card-title">List of system logs</h5>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Log number</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Details</th>
                                            <th scope="col">Date performed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($logs as $log)
                                            <tr>
                                                <th scope="row">{{ $log->id }}</th>
                                                <td>{{ $log->title }}</td>
                                                <td>{{ $log->details }}</td>
                                                <td>{{ $log->created_at }}</td>
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
                                    {{ $logs->links( 'pagination::bootstrap-5' ) }}
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
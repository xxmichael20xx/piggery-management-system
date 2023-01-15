@php
    use Illuminate\Support\Str;

    $formSessions = [
        'order.update_success', 'action.failed'
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

@extends('layouts.admin', [ 'pageTitle' => 'Manage Orders' ])

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
                        <h5 class="card-title">List of {{ $type == 'sold' ? 'Sold' : 'Pending' }} Orders</h5>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order Number</th>
                                            <th scope="col" style="width: 20%;">Image</th>
                                            <th scope="col">Pig Number</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">{{ $type == 'sold' ? 'Date Sold' : 'Date Ordered' }}</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <th scope="row">{{ $order->id }}</th>
                                                <td>
                                                    <img src="{{ asset('uploads/pigs/images/' . $order->pig->image) }}" class="img-fluid" width="250" height="250">
                                                </td>
                                                <td>{{ $order->pig->pig_no }}</td>
                                                <td>₱{{ number_format( $order->amount ) }}</td>
                                                <td>{{ $type == 'sold' ? $order->updated_at : $order->created_at }}</td>
                                                <td>
                                                    @if ($type !== 'sold')
                                                        <button type="button" class="btn btn-dark update--order" data-action="cancel" data-id="{{ $order->id }}"><i class="fa fa-times"></i> Cancel Order</button>
                                                        <button type="button" class="btn btn-primary update--order" data-action="finish" data-id="{{ $order->id }}"><i class="fa fa-check"></i> Finish Order</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" align="center">No result(s) found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-5">
                                <div class="col custom__pagination">
                                    {{ $orders->links( 'pagination::bootstrap-5' ) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form id="order--update-form" method="POST" action="{{ route('admin.order_update') }}">
        @csrf

        <input type="hidden" name="action_id" id="action_id">
        <input type="hidden" name="action_type" id="action_type">
    </form>
@endsection

@section('js')
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const actionForm = document.getElementById( 'order--update-form' )
            const actionId = document.getElementById( 'action_id' )
            const actionType = document.getElementById( 'action_type' )
            const updateButtons = document.querySelectorAll( '.update--order' )
            if ( updateButtons.length > 0 ) {
                updateButtons.forEach( el => {
                    el.addEventListener('click', () => {
                        const id = el.getAttribute( 'data-id' )
                        const type = el.getAttribute( 'data-action' )
                        const text = type == 'cancel' ? 'Pig Order will be canceled!' : 'Pig Order will be marked as Sold and Pig will not be available anymore!'
                        actionId.value = id
                        actionType.value = type

                        Swal.fire({
                            icon: 'warning',
                            title: 'Are you sure?',
                            text: text,
                            showCancelButton: true,
                            showConfirmButton: true,
                            cancelButtonText: 'Cancel',
                            confirmButtonText: 'Confirm'
                        }).then( (e) => {
                            if ( e.isConfirmed ) {
                                actionForm.submit()
                            }
                        } )
                    })
                } )
            }
        });
    </script>
@endsection
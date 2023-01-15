@php
    use Illuminate\Support\Str;

    $formSessions = [
        'update_pig.success', 'update_pig.failed',
        'add_to_orders.success', 'add_to_orders.failed',
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

@extends('layouts.admin', [ 'pageTitle' => 'Pig Details', 'back' => route('admin.pigs') ])

@section('content')
    @if ($formSessionMessage)
        <div class="alert alert-dark" role="alert">
            <label class="lead">{{ $formSessionMessage }}</label>
        </div>
    @endif

    <section class="row">
        <div class="col-6">
            <div class="card border-0 shadow rounded">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.update_pig', ['id' => $pig->id]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-12 text-end">
                                @if ($pig->status == 'active')
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addToOrdersModal">
                                        <i class="fa fa-dollar"></i> Move to Orders
                                    </button>
                                @else
                                    @if ($pig->status == 'pending_orders')
                                        Note: Pig is in the list of Orders!
                                    @elseif($pig->status == 'sold')
                                        Note: Pig has been already sold!
                                    @else
                                        Note: Pig with an status of "Active" will be able to be moved to orders!
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-6 mx-auto text-center">
                                <img src="{{ asset('uploads/pigs/images/' . $pig->image) }}" class="img-fluid" id="previewImage" width="250" height="250">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload pig image</label>
                                    <input class="form-control" type="file" name="image" id="image" accept="image/*">
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="pig_no" class="lead">Pig No</label>
                                <input type="text" name="pig_no" id="pig_no" class="form-control @error('pig_no') is-invalid @enderror" value="{{ $pig->pig_no }}" readonly required>

                                @error('pig_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="breed_id" class="lead">Breed</label>
                                <select name="breed_id" id="breed_id" class="form-control @error('breed_id') is-invalid @enderror">
                                    <option value="" selected disabled>Select a breed</option>
                                    @forelse ($breeds as $breed)
                                        <option value="{{ $breed->id }}" {{ $pig->breed_id == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                    @empty
                                        <option value="" disabled>No available breed</option>
                                    @endforelse
                                </select>

                                @error('breed_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="breed_id" class="lead">Weight</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" min="1" max="5000" value="{{ $pig->weight }}" required>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="weight_unit" id="weight_unit" class="form-control @error('weight_unit') is-invalid @enderror" value="kg" readonly required>
                                    </div>
                                </div>

                                @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="lead">Gender</label>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ $pig->gender == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ $pig->gender == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_female">
                                        Female
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="lead">Date Arrived</label>
                                <input type="date" name="date_arrived" id="date_arrived" class="form-control @error('date_arrived') is-invalid @enderror" value="{{ $pig->date_arrived }}" required>

                                @error('date_arrived')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            
                            <div class="col-12 mb-3">
                                <label class="lead">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="" selected disabled>Select a status</option>
                                    @forelse ($statuses as $status)
                                        <option value="{{ $status }}" {{ $pig->status == $status ? 'selected' : '' }}>{{ Str::headline( $status ) }}</option>
                                    @empty
                                        <option value="" disabled>No available status</option>
                                    @endforelse
                                </select>

                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="lead">Notes <small class="text-muted" id="notes-helptext">(Optional)</small></label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes">{{ $pig->notes }}</textarea>

                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mb-3 {{ $pig->quarantine ? '' : 'collapse' }}" id="quarantined--remarks">
                                <label class="lead">Quarantined remarks <small class="text-muted">(Required)</small></label>
                                <textarea class="form-control @error('quarantine_remarks') is-invalid @enderror" name="quarantine_remarks" id="quarantine_remarks">{{ $pig->quarantine->reason ?? '' }}</textarea>

                                @error('quarantine_remarks')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if ( $pig->quarantine )
                                <div class="col-12 mb-3">
                                    <label class="lead fw-bold">Date Quarantined: {{ $pig->quarantine->created_at }}</label>
                                </div>
                            @endif

                            <div class="col-12 text-center">
                                <button type="buton" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>

                    <div class="modal fade" id="addToOrdersModal" tabindex="-1" aria-labelledby="addToOrdersModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.add_to_orders', ['id' => $pig->id]) }}" enctype="multipart/form-data" id="add--to-orders-form">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="addToOrdersModalLabel">Move Pig to Orders</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="amount" class="lead">Amount</label>
                                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" min="1" max="50000" value="{{ old('amount') }}" required>

                                                @error('amount')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', (event) => {
                const addPigModal = new bootstrap.Modal('#addPigModal', {})
                addPigModal.show()
            });
        </script>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

@section('js')
    <script>
        let soldState = false
        window.addEventListener('DOMContentLoaded', (event) => {
            const status = document.getElementById('status')
            if ( status ) {
                status.addEventListener('change', () => {
                    const value = status.value
                    if ( value == 'quarantined' ) {
                        document.getElementById( 'quarantined--remarks' ).classList.remove( 'collapse' )
                        document.getElementById( 'quarantine_remarks' ).setAttribute( 'required', true )
                    } else {
                        document.getElementById( 'quarantined--remarks' ).classList.add( 'collapse' )
                        document.getElementById( 'quarantine_remarks' ).removeAttribute( 'required' )
                        document.getElementById( 'quarantine_remarks' ).value = ''
                    }
                })
            }

            const image = document.getElementById( 'image' )
            image.addEventListener( 'change', ( e ) => {
                const files = e.target.files
                
                if ( files.length > 0 ) {
                    const extension = files[0].name.substring(files[0].name.lastIndexOf('.') + 1, files[0].name.length);
                    const images = [ 'jpg', 'jpeg', 'png' ]

                    if ( images.indexOf( extension ) == -1 ) return false

                    const src = URL.createObjectURL( files[0] )
                    const preview = document.getElementById( 'previewImage' )
                    preview.src = src
                    preview.classList.remove( 'collapse' )
                }
            } )

            const addToOrdersForm = document.getElementById( 'add--to-orders-form' )
            if ( addToOrdersForm ) {
                addToOrdersForm.addEventListener('submit', (e) => {
                    if ( ! soldState ) {
                        e.preventDefault()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Are you sure?',
                            text: 'Pig will be moved to orders and updating Pig details will be restricted!',
                            showCancelButton: true,
                            showConfirmButton: true,
                            cancelButtonText: 'Cancel',
                            confirmButtonText: 'Confirm'
                        }).then( (e) => {
                            if ( e.isConfirmed ) {
                                soldState = true
                                addToOrdersForm.submit()
                            }
                        } )
                    }
                })
            }
        });
    </script>
@endsection
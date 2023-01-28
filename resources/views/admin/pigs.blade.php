@php
    use Illuminate\Support\Str;

    $formSessions = [
        'new_pig.success', 'new_pig.failed'
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

@extends('layouts.admin', [ 'pageTitle' => 'Manage Pigs' ])

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
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title m-0 p-0">List of pigs</h5>

                            <button type="button" class="btn btn-primary h-100" data-bs-toggle="modal" data-bs-target="#addPigModal">
                                Add Pig
                            </button>
                            <div class="modal fade" id="addPigModal" tabindex="-1" aria-labelledby="addPigModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.new_pig') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="addPigModalLabel">New Pig Form</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6 mx-auto mb-3">
                                                        <img class="img-fluid collapse" id="previewImage">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="mb-3">
                                                            <label for="image" class="form-label">Upload pig image</label>
                                                            <input class="form-control" type="file" name="image" id="image" accept="image/*" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label for="pig_no" class="lead">Pig No</label>
                                                        <input type="text" name="pig_no" id="pig_no" class="form-control @error('pig_no') is-invalid @enderror" value="pig_no_{{ $pigsCount + 1 }}" readonly required>

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
                                                                <option value="{{ $breed->id }}" {{ $breed->id == old('breed_id') ? 'selected' : '' }}>{{ $breed->name }}</option>
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
                                                        <label for="weight" class="lead">Weight</label>
                                                        <div class="row">
                                                            <div class="col">
                                                                <input type="number" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" min="1" max="5000" value="{{ old('weight') }}" required>
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
                                                            <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" checked>
                                                            <label class="form-check-label" for="gender_male">
                                                                Male
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female">
                                                            <label class="form-check-label" for="gender_female">
                                                                Female
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label class="lead">Date Arrived</label>
                                                        <input type="date" name="date_arrived" id="date_arrived" class="form-control @error('date_arrived') is-invalid @enderror" value="{{ old('date_arrived') }}" required>

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
                                                                @php
                                                                    if ( $status == 'on_treatment' || $status == 'deceased' ) continue;
                                                                @endphp
                                                                <option value="{{ $status }}" {{ $status == old('status') ? 'selected' : '' }}>{{ Str::headline( $status ) }}</option>
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
                                                        <label class="lead">Notes <small class="text-muted">(Optional)</small></label>
                                                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes">{{ old('notes') }}</textarea>

                                                        @error('notes')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-12 mb-3 {{ $errors->has('quarantine_remarks') ? '' : 'collapse' }}" id="quarantined--remarks">
                                                        <label class="lead">Quarantined remarks <small class="text-muted">(Required)</small></label>
                                                        <textarea class="form-control @error('quarantine_remarks') is-invalid @enderror" name="quarantine_remarks" id="quarantine_remarks">{{ old('quarantine_remarks') }}</textarea>

                                                        @error('quarantine_remarks')
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
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 10%;">Pig Number</th>
                                        <th scope="col" style="width: 20%;">Image</th>
                                        <th scope="col" style="width: 10%;">Breed</th>
                                        <th scope="col" style="width: 10%;">Weight</th>
                                        <th scope="col" style="width: 20%;">Date Arrived</th>
                                        <th scope="col" style="width: 10%;">Status</th>
                                        <th scope="col" style="width: 20%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pigs as $pig)
                                        <tr>
                                            <th scope="row">{{ $pig->pig_no }}</th>
                                            <td>
                                                <img src="{{ asset('uploads/pigs/images/' . $pig->image) }}" class="img-fluid" width="250" height="250">
                                            </td>
                                            <td>{{ $pig->breed->name }}</td>
                                            <td>{{ $pig->weight }}{{ $pig->weight_unit }}</td>
                                            <td>{{ $pig->date_arrived }}</td>
                                            <td>
                                                @php
                                                    $status_color = 'success';
                                                    $status_text = 'healthy';

                                                    switch ($pig->status) {
                                                        case 'healthy':
                                                            $status_color = 'success';
                                                            $status_text = 'Healthy';
                                                            break;

                                                        case 'unhealthy':
                                                            $status_color = 'warning';
                                                            $status_text = 'Unhealthy';
                                                            break;

                                                        case 'on_treatment':
                                                            $status_color = 'info';
                                                            $status_text = 'On Treatment';
                                                            break;

                                                        case 'quarantined':
                                                            $status_color = 'danger';
                                                            $status_text = 'Quarantined';
                                                            break;
                                                        
                                                        default:
                                                            $status_color = 'dark';
                                                            $status_text = 'Deceased';
                                                            break;
                                                    }
                                                @endphp

                                                <div class="text-{{ $status_color }}">
                                                    <i class="fa-solid fa-circle"></i> {{ $status_text }}
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.show_pig', ['id' => $pig->id]) }}" class="btn btn-primary">
                                                    <i class="fa-solid fa-eye"></i> View/Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" align="center">No result(s) found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-5">
                            <div class="col custom__pagination">
                                {{ $pigs->links( 'pagination::bootstrap-5' ) }}
                            </div>
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
@endsection

@section('js')
    <script>
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
        });
    </script>
@endsection
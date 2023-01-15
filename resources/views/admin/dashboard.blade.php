@extends('layouts.admin', [ 'pageTitle' => 'Dashboard' ])

@section('content')
    <section class="row mb-3">
        <div class="col-12 col-md-2">
            <div class="card border-0 shadow rounded">
                <div class="card-body p-3">
                    <h5 class="card-title">Pigs <span class="text-help">| Total count</span></h5>

                    <div class="d-flex justify-content-center">
                        <h2 class="text-success">
                            <i class="fa-solid fa-piggy-bank"></i>
                            {{ $totalPigs }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="card border-0 shadow rounded">
                <div class="card-body p-3">
                    <h5 class="card-title">Breeds <span class="text-help">| Total count</span></h5>

                    <div class="d-flex justify-content-center">
                        <h2 class="text-info">
                            <i class="fa-solid fa-bars-staggered"></i>
                            {{ $totalBreeds }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="card border-0 shadow rounded">
                <div class="card-body p-3">
                    <h5 class="card-title">Quarantine <span class="text-help">| Total count</span></h5>

                    <div class="d-flex justify-content-center">
                        <h2 class="text-danger">
                            <i class="fa-solid fa-house-lock"></i>
                            {{ $totalQuarantines }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="card border-0 shadow rounded">
                <div class="card-body p-3">
                    <h5 class="card-title">Sales <span class="text-help">| Total count</span></h5>

                    <div class="d-flex justify-content-center">
                        <h2 class="text-dark">
                            ₱ {{ number_format($totalSales) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row mb-3">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <h5 class="card-title">Recently added pigs this pas 7 days</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Pig Number</th>
                                        <th scope="col">Breed</th>
                                        <th scope="col">Weight</th>
                                        <th scope="col">Date Arrived</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($totalPigsSevenDays as $totalPigsSevenDay)
                                        <tr>
                                            <th scope="row">{{ $totalPigsSevenDay->pig_no }}</th>
                                            <td>{{ $totalPigsSevenDay->breed->name }}</td>
                                            <td>{{ $totalPigsSevenDay->weight }}{{ $totalPigsSevenDay->weight_unit }}</td>
                                            <td>{{ $totalPigsSevenDay->date_arrived }}</td>
                                            <td>
                                                @php
                                                    $status_color = 'success';
                                                    $status_text = 'Active';

                                                    switch ($totalPigsSevenDay->status) {
                                                        case 'active':
                                                            $status_color = 'success';
                                                            $status_text = 'Active';
                                                            break;

                                                        case 'inactive':
                                                            $status_color = 'warning';
                                                            $status_text = 'Inactive';
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" align="center">No result(s) found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-5">
                            <div class="col custom__pagination">
                                {{ $totalPigsSevenDays->links( 'pagination::bootstrap-5' ) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
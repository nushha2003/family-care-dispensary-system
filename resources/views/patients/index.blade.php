@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Patients</h1>
        <a href="{{ route('patients.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Register New Patient
        </a>
    </div>

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('patients.index') }}" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search by name, phone, or email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Allergies</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td>{{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}</td>
                            <td>{{ $patient->full_name }}</td>
                            <td>{{ ucfirst($patient->gender) }}</td>
                            <td>{{ $patient->phone }}</td>
                            <td>{{ $patient->email ?? '-' }}</td>
                            <td>{{ $patient->allergies ?? 'None' }}</td>
                            <td>
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}" method="POST"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Delete this patient?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No patients found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection
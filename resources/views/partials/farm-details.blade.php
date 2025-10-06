<div class="row">
    <div class="col-md-6">
        <h6><strong>Farm Information</strong></h6>
        <p><strong>Farm ID:</strong> {{ $farmId }}</p>
        <p><strong>Name:</strong> {{ $farm->name ?? 'N/A' }}</p>
        <p><strong>Location:</strong> {{ $farm->location ?? 'N/A' }}</p>
        <p><strong>Size:</strong> {{ $farm->size_hectares ?? $farm->size ?? 'N/A' }} hectares</p>
        <p><strong>Status:</strong> <span class="badge badge-{{ ($farm->status ?? 'active') === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($farm->status ?? 'active') }}</span></p>
    </div>
    <div class="col-md-6">
        <h6><strong>Owner Information</strong></h6>
        <p><strong>Name:</strong> {{ $owner->name ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $owner->email ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $owner->phone ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $owner->address ?? 'N/A' }}</p>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <h6><strong>Description</strong></h6>
        <p>{{ $farm->description ?? 'No description available.' }}</p>
    </div>
</div>

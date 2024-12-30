@extends('frontend.dashboard.dashboard-master')

@section('dashboard-content')

    @php
        $id = Auth::user()->id;
        $profileData = App\Models\User::find($id);
    @endphp

    <section class="section pt-4 pb-4 osahan-account-page">
        <div class="container">
            <div class="row">

                @include('frontend.dashboard.partials.sidebar')


                <div class="col-md-9">
                    <div class="osahan-account-page-right rounded shadow-sm bg-white p-4 h-100">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                <h4 class="font-weight-bold mt-0 mb-4">User Profile </h4>


                                <div class="bg-white card mb-4 order-list shadow-sm">
                                    <div class="gold-members p-4">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div>
                                                    <div class="mb-3">
                                                        <label for="example-text-input" class="form-label">Name</label>
                                                        <div class="form-control bg-light text-dark" id="example-text-input">{{ $profileData->name }}</div>
                                                    </div>
                                        
                                                    <div class="mb-3">
                                                        <label for="example-text-input" class="form-label">Email</label>
                                                        <div class="form-control bg-light text-dark" id="example-text-input">{{ $profileData->email }}</div>
                                                    </div>
                                        
                                                    <div class="mb-3">
                                                        <label for="example-text-input" class="form-label">Phone</label>
                                                        <div class="form-control bg-light text-dark" id="example-text-input">{{ $profileData->phone }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="col-lg-6">
                                                <div class="mt-3 mt-lg-0">
                                                    <div class="mb-3">
                                                        <label for="example-text-input" class="form-label">Address</label>
                                                        <div class="form-control bg-light text-dark" id="example-text-input">{{ $profileData->address }}</div>
                                                    </div>
                                        
                                                    <div class="mb-3">
                                                        <img id="showImage"
                                                            src="{{ !empty($profileData->photo) ? url('upload/user_images/' . $profileData->photo) : url('upload/no_image.jpg') }}"
                                                            alt="" class="rounded-circle p-1 bg-primary" width="110">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

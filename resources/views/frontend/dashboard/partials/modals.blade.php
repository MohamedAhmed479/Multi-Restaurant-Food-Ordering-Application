@php
    $user = Auth::user();
@endphp
    <!-- Modal -->
    <div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-labelledby="edit-profile"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-profile">Edit profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="d-flex justify-content-center">
                            <img id="showImage" class="mb-3 rounded-circle shadow-sm mt-1 img-fluid"
                                src="{{ !empty($user->photo) ? asset("upload/user_images/$user->photo") : asset('upload/no_image.jpg') }}"
                                alt="gurdeep singh osahan" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Name
                                </label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control"
                                    placeholder="Enter Your Name">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Phone number
                                </label>
                                <input type="text" name="phone" value="{{ $user->phone }}" class="form-control"
                                    placeholder="Enter Phone number">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Address
                                </label>
                                <input type="text" name="address" value="{{ $user->address }}" class="form-control"
                                    placeholder="Enter Your Address">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Profile Photo
                                </label>
                                <input type="file" name="photo" id="image" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Email
                                </label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control"
                                    placeholder="Enter Your Email
                              ">
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <label>New Password
                                </label>
                                <input type="password" name="new_password" class="form-control"
                                    placeholder="Optional
                              ">
                            </div>
                            <div class="form-group col-md-12 mb-0">
                                <label>Confirm New Password
                                </label>
                                <input type="password" name="new_password_confirmation" class="form-control"
                                    placeholder="Optional
                              ">
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <label>Password
                                </label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter your password to update your profile information.
                              ">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="btn d-flex w-50 text-center justify-content-center btn-outline-primary"
                                data-dismiss="modal">CANCEL</button>
                            <button type="submit"
                                class="btn d-flex w-50 text-center justify-content-center btn-primary">UPDATE</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="add-address-modal" tabindex="-1" role="dialog" aria-labelledby="add-address"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-address">Add Delivery Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Delivery Area</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Delivery Area">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="button-addon2"><i class="icofont-ui-pointer"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Complete Address
                                </label>
                                <input type="text" class="form-control"
                                    placeholder="Complete Address e.g. house number, street name, landmark">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Delivery Instructions
                                </label>
                                <input type="text" class="form-control"
                                    placeholder="Delivery Instructions e.g. Opposite Gold Souk Mall">
                            </div>
                            <div class="form-group mb-0 col-md-12">
                                <label for="inputPassword4">Nickname
                                </label>
                                <div class="btn-group btn-group-toggle d-flex justify-content-center"
                                    data-toggle="buttons">
                                    <label class="btn btn-info active">
                                        <input type="radio" name="options" id="option1" autocomplete="off"
                                            checked>
                                        Home
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="options" id="option2" autocomplete="off"> Work
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="options" id="option3" autocomplete="off">
                                        Other
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn d-flex w-50 text-center justify-content-center btn-outline-primary"
                        data-dismiss="modal">CANCEL
                    </button><button type="button"
                        class="btn d-flex w-50 text-center justify-content-center btn-primary">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="delete-address-modal" tabindex="-1" role="dialog"
        aria-labelledby="delete-address" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-address">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 text-black">Are you sure you want to delete this xxxxx?</p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn d-flex w-50 text-center justify-content-center btn-outline-primary"
                        data-dismiss="modal">CANCEL
                    </button><button type="button"
                        class="btn d-flex w-50 text-center justify-content-center btn-primary">DELETE</button>
                </div>
            </div>
        </div>
    </div>

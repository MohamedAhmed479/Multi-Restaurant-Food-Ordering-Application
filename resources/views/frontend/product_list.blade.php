@foreach ($filterProducts as $product)

@php
$reviewcount = App\Models\Review::where('client_id', $product->resturant->id)
    ->where('status', 1)
    ->latest()
    ->get();
$avarage = App\Models\Review::where('client_id', $product->resturant->id)
    ->where('status', 1)
    ->avg('rating');
@endphp

    {{-- @dump($product) --}}
    <div class="col-md-4 col-sm-6 mb-4 pb-2">
        <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
            <div class="list-card-image">

                <div class="star position-absolute"><span class="badge badge-success"><i class="icofont-star"></i> {{ number_format($avarage, 1) }}
                    ({{ count($reviewcount) }}+)
                    </span></div>

                {{-- Product Promoted Or Not --}}
                @if ($product->resturant->coupons->first())
                    <div class="member-plan position-absolute"><span class="badge badge-dark">Promoted</span></div>
                @endif

                {{-- Product Image --}}
                <a href="{{ route('resturant.details', ['client' => $product->resturant]) }}">
                    <img src="{{ asset($product->image) }}" class="img-fluid item-img">
                </a>

            </div>
            <div class="p-3 position-relative">
                <div class="list-card-body">
                    <h6 class="mb-1"><a href="{{ route('resturant.details', ['client' => $product->resturant]) }}"
                            class="text-black"> {{ $product->name }}</a></h6>

                    <p class="text-gray mb-3 time"><span class="bg-light text-dark rounded-sm pl-2 pb-1 pt-1 pr-2"><i
                                class="icofont-wall-clock"></i> 20–25 min</span> <span
                            class="float-right text-black-50"> {{ $product->price }}</span></p>
                </div>

                @if ($product->resturant->coupons->count() > 0)
                    <span class="badge badge-success">OFFER</span>
                    <small>{{ $product->resturant->coupons->where('status', 1)->first()->discount }}%
                        off | Use Coupon
                        {{ $product->resturant->coupons->where('status', 1)->first()->coupon_name }}</small>
                @else
                    <span class="badge badge-danger">OFFER</span>
                    <small>Right Now There Is No Coupon</small>
                @endif

            </div>
        </div>
    </div>
@endforeach

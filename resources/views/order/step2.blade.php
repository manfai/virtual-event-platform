@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid" data-center="background-size: 100%;background:url('/img/banner.jpg') center;" data-top-bottom="background-size: 110%;">
    <div class="container">
        <div class="jumbo-heading" data-aos="fade-up">
            <h1>Checkout</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Cart</li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <p class="subtitle text-primary">Checkout</p>
                <h1 class="h2 mb-5">Shopping Cart <strong>{{count($cartItems)}}</strong>Items  <span class="text-muted float-right">Payment</span> </h1>
                <div class="text-block">
                    <div class="alert alert-warning text-sm mb-3">
                        <div class="media align-items-center">
                            <!-- <svg class="svg-icon svg-icon svg-icon-light w-2rem h-2rem mr-3 text-reset">
                                <use xlink:href="#secure-payment-1"> </use>
                            </svg> -->
                            <div class="media-body">選購貨品淨總額多於<strong> $ 250.00 </strong>即可免<strong> $ 40.00 </strong>基本運費 。</div>
                        </div>
                    </div>
                </div>
                <form action="{{route('orders.store')}}" method="POST">@csrf
                  <input type="hidden" name="step" value="payment">
                  <div class="text-block">
                      <div class="d-flex justify-content-between align-items-end mb-4">
                          <h5 class="mb-0">Payment method</h5>
                          <div class="text-muted">
                            <i class="fab fa-cc-paypal fa-2x mr-2"> </i>
                            <i class="fab fa-cc-visa fa-2x mr-2"> </i>
                            <i class="fab fa-cc-mastercard fa-2x mr-2"></i>
                            <i class="fab fa-cc-apple-pay fa-2x"></i>
                          </div>
                      </div>
                      <select name="payment" id="form_payment" data-style="btn-selectpicker" class="selectpicker form-control mb-3">
                        @foreach($payment as $option)
                        <option value="{{$option->id}}" data-card="{{$option->card_data}}">{{$option->title}}</option>
                        @endforeach
                      </select>
                      <!-- <button type="button" data-toggle="collapse" data-target="#addNewCard" aria-expanded="false" aria-controls="addNewCard" data-expanded-text="Close" data-collapsed-text="信用卡資料" class="btn btn-link btn-collapse pl-0 text-muted">信用卡資料</button> -->
                      <div id="credit-card" class="row">
                        <div class="form-group col-md-6">
                          <label for="card-name" class="form-label">Card Holder Name</label>
                          <input type="text" name="card-name" placeholder="Name on card" id="card-name" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="card-number" class="form-label">Card Number</label>
                          <input type="text" name="card-number" placeholder="Card number" id="card-number" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="expiry-date" class="form-label">Exp Date</label>
                          <input type="text" name="expiry-date" placeholder="MM/YY" id="expiry-date" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="cvv" class="form-label">CVC/CVV</label>
                          <input type="text" name="cvv" placeholder="123" id="cvv" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="zip" class="form-label">Zip Code（Optional）</label>
                          <input type="text" name="zip" placeholder="123" id="zip" class="form-control">
                        </div>
                      </div>
                  </div>
                  <div class="text-block">
                    <h6>Refund Policy</h6>
                    <p class="text-sm text-muted">派送之貨品不能與商戶派送之貨品合拼計算運費。 </p>
                    <p class="text-sm text-muted">商戶派送之貨品須於該商店內消費指定金額才可免商戶運費。</p>
                  </div>
                  <div class="row form-block flex-column flex-sm-row">
                      <div class="col text-center text-sm-left">
                          <a href="{{route('orders.step.1')}}" class="btn btn-warning text-muted"> <i class="fa-chevron-left fa mr-2"></i>上一步</a>
                      </div>
                      <div class="col text-center text-sm-right"><button type="submit"
                              class="btn btn-primary px-3">
                              Next Step for Confirm
                      <i class="fa-chevron-right fa ml-2"></i></button></div>
                  </div>

                </form>

            </div>
            <div class="col-lg-5 pl-xl-5">
                @include('components.cart')
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>

$(document).ready(function () {
  needCard();
  $('#form_payment').on('change', function() {
      needCard();
  });
});

function needCard(){
  var need = $('#form_payment').find(':selected').data('card');
  if(need!=0){
      $('#credit-card').show();
  } else {
      $('#credit-card').hide();
  }
}

</script>
@endpush

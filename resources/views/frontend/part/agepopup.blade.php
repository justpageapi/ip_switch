<div class="modal fade" id="agepopup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm py-3">    
      <div class="modal-body" id="age_popup">
        <h3 class="text-uppercase text-center mb-0">
           Are you 18+ ?
        </h3>
        @php
          $msg = App\Models\SiteSetting::getSitesetting('web_popup','offer_text');  
        @endphp
        @if(isset($msg))
          {{--<p class="mt-3 text-center">{{$msg->key}} Min Order {{config('currency.symbol')}}{{$msg->value}}</p> --}}
          <p class="mt-3 text-center">{{$msg}}</p>
        @endif
      </div>
      <div class="modal-footer justify-content-center p-1 border-0 mb-2">          
        <button type="button" class="btn btn-default btn-theme age_btn px-3" data-val="yes">Yes</button>
        <button type="button" class="btn btn-secondary age_btn px-3" data-val="no">No</button>
      </div>
    </div>
  </div>
</div>
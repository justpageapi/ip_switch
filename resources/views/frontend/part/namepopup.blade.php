@if(Auth::check())
  @if(Auth::user()->username == null)
  <div class="modal fade" id="namepopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content border-0 shadow-sm"> 
        <div class="modal-header">
          <h5 class="modal-title">Enter Username</h5>
        </div> 
        <form action="{{route('setUsername')}}" method="post">
          @csrf  
          <div class="modal-body">           
            <input class="form-control" name="name" type="text" placeholder="Enter Name" required>
          </div>
          <div class="modal-footer text-left p-1 border-0 mb-2">          
            <button type="submit" class="btn btn-default btn-theme px-3 w-100">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif
@endif
<div class="filter">
  <div class="accordion" id="accordionExample">

    <div class="card shadow">
      {{-- <div class="card-header pt-0 pb-0">
        <div class="row">
          <div class="col align-self-center">
            <h6 class="mb-0">FIND YOUR PRODUCT</h6>
          </div>
          <div class="col-auto px-0">
            <button class="btn btn-link text-danger filter-close">
              <i class="bi bi-x size-22"></i>
            </button>
          </div>
        </div>
      </div> --}}
      
      <div class="card-body overflow-auto p-0">
        <div class="card shadow-sm mb-0">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingBrand">
              <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="true" aria-controls="collapseBrand">
                Filter By Brand
              </button>
            </h2>
            <div id="collapseBrand" class="accordion-collapse collapse" aria-labelledby="headingBrand" data-bs-parent="#accordionExample">
              <div class="accordion-body p-2">
                <ul class="list-group list-group-flush filter_data"></ul>
              </div>
            </div>
          </div>
          {{-- <div class="accordion-item">
            <h2 class="accordion-header" id="headingCategory">
              <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                Filter By Category
              </button>
            </h2>
            <div id="collapseCategory" class="accordion-collapse collapse" aria-labelledby="headingCategory" data-bs-parent="#accordionExample">
              <div class="accordion-body p-2">
                <ul class="list-group list-group-flush category_data">
              </div>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
  </div>
</div>

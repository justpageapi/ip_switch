<div class="col">
  <div class="product-card">
    <div class="product-media text-center">
      <div class="product-label"><label class="label-text order">ID :</label></div>
      <a class="product-wish wish" href="{{ route('product_details',$p->id) }}"><i class="fas fa-eye"></i></a>
      <a class="product-image" href="{{ route('product_details',$p->id) }}"><img src="{{ $p->productimage[0]->imagepath }}" alt="product" /></a>
    </div>
    <div class="product-content">
      <div class="product-rating">
        {{ $p->category->name }}
      </div>
      <h6 class="product-name"><a href="{{ route('product_details',$p->id) }}">{{ $p['name'] }}</a></h6>
      <h6 class="product-price">
        <del>{{ $p['mrp']}}</del><span> {{ $p['price'] }} <small>/piece</small></span>
      </h6>
      @php 
        //$d=BackendHelper::check_in_cart($p->id);
      @endphp
      
      <button class="product-add" data-id="{{ $p['id'] }}" data-price="{{ $p['price'] }}" title="Add to Cart" style="display:<?php echo $d[1];?>;"><i class="fas fa-shopping-basket"></i><span>ADD</span></button>

      <div class="product-action" style="display:<?php echo $d[0];?>;">
        <button data-id="{{ $p['id'] }}" class="action-minus" title="Quantity Minus"><i class="icofont-minus"></i></button>
        <input class="action-input" title="Quantity Number" type="text" name="quantity" value="{{$d[2]}}" />
        <button data-id="{{ $p['id'] }}" class="action-plus" title="Quantity Plus"><i class="icofont-plus"></i></button>
      </div>

    </div>
  </div>
</div>


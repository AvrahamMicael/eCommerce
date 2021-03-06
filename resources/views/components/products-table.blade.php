<table class="table">
    <thead>
        <tr>
            <th scope="col">Product</th>
            <th scope="col">Price</th>
            @if ($locale != 'wishlist')
                <th scope="col">Quantity</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $key => $product)
            <tr>
                <td><a href="{{route('products.show', $product->id)}}" class="link-dark">{{$product->name}}</a></td>
                <td>
                    <div class="row justify-content-between">
                        ${{number_format($product->price, 2)}}
                        @if ($locale == 'wishlist')
                            <div class="col-8" align="end">
                                <form action="{{route('cart.wishlist')}}" method="post" class="d-inline">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" name="row_id" value="{{$product->rowId}}">
                                    <button type="submit" class="btn btn-outline-primary" title="Add to Cart">
                                        <i class="fa-solid fa-cart-arrow-down"></i>
                                    </button>
                                </form>
                                <form action="{{route('cart.wishlist')}}" method="post" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="row_id" value="{{$product->rowId}}">
                                    <button type="submit" class="btn btn-outline-danger" title="Remove from Wishlist">
                                        <i class="fa-solid fa-x"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </td>

                @if ($locale != 'wishlist')
                    <td>
                        @if ($locale == 'cart')
                            <form action="{{route('cart.edit.qty')}}" method="post">
                                <div class="row justify-content-between">
                                    <div class="col-2">
                                        <input type="number"
                                            value="{{$product->qty}}"
                                            placeholder="{{$product->qty}}"
                                            name="qty"
                                            min="1"
                                            max="100"
                                            class="form-control"
                                        >
                                    </div>
                                    <?php $out_of_stock[$key] = $product->qty > $product->model->qty ?>
                                    @if ($out_of_stock[$key])
                                        <div class="col-2">
                                            <span class="badge rounded-pill text-light bg-danger">
                                                In Stock: {{ $product->model->qty }}
                                            </span>
                                        </div>
                                    @endif
                                    @csrf
                                    @method('patch')    
                                    <input type="hidden" name="row_id" value="{{$product->rowId}}">
                                    <span class="col-7" align="end">
                                        <button type="submit" class="btn btn-outline-primary" title="Edit quantity"><i class="fas fa-edit"></i></button>
                                        <button formaction="{{route('cart.remove.product')}}" type="submit" class="btn btn-outline-danger" title="Remove from chart"><i class="fa-solid fa-x"></i></button>
                                    </span>        
                                </div>
                            </form>    
                        @else
                            {{$product->qty}}
                        @endif
                    </td>
                @endif                
            </tr>
        @endforeach
        @if ($locale != 'orders.show')
            <tr>
                <th scope="row">Total:</th>
                <td {{ in_array($locale, ['checkout', 'wishlist']) || $products->count() == 0 ? 'colspan=2' : null  }}>
                    <div class="row">
                        ${{Cart::total()}}
                    </div>
                </td>
                @if ($locale == 'cart' && $products->count() > 0)
                    <td align="end">
                        <a href="{{route('checkout.index')}}"
                            class="btn btn-outline-success {{ isset($out_of_stock) && in_array(true, $out_of_stock) ? 'disabled' : null }}"
                        >
                            Proceed to Checkout
                        </a>
                    </td>
                @endif
            </tr>
        @endif
    </tbody>
</table>

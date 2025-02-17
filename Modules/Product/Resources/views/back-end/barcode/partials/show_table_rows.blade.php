@forelse ($products as $product)
    <tr>
        <td>


                {{ $product->product_name }}
            <input type="hidden" name="products[{{ $loop->index + $index }}][product_id]"
                value="{{ $product->id }}">

        </td>
        <td>
            {{ $product->sku }}
        </td>
        <td>
            <input type="number" class="form-control" min=1 name="products[{{ $loop->index + $index }}][quantity]"
                value="1">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-times"></i></button>
        </td>
    </tr>
@empty
@endforelse

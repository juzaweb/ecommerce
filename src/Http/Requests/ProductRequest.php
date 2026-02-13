<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Ecommerce\Enums\ProductStatus;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'inventory' => ['required', 'in:1,0'],
			'price' => ['required', 'numeric', 'min:0'],
			'compare_price' => ['nullable', 'numeric', 'min:0'],
			'status' => ['required', Rule::enum(ProductStatus::class)],
            'stock_quantity' => ['required_if:inventory,1', 'nullable', 'integer', 'min:0'],
            'sku_code' => ['nullable'],
            'barcode' => ['nullable'],
			'name' => ['required'],
			'content' => ['nullable'],
		];
    }
}

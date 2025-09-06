<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        $locale = $this->input('locale', config('translatable.fallback_locale'));

        return [
            "{$locale}.name" => ['required', 'string', 'max:255'],
            "{$locale}.description" => ['nullable', 'string'],
        ];
    }
}

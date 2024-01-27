<tr>
    <td>
        <input type="text"
               name="download_links[{{ $marker }}][name]"
               class="form-control"
               value="{{ $item->name ?? '' }}"
        />
    </td>
    <td>
        {{ Field::uploadUrl(
            trans('File Url'),
            "download_links[{$marker}][url]",
            [
                'placeholder' => 'https://',
                'show_label' => false,
                'id' => "download-links-{$marker}",
                'disk' => 'protected',
                'value' => $item->url ?? '',
            ]
        ) }}
    </td>
</tr>


<div class="section-title">Terms & Conditions</div>
<div class="table-responsive">
    <table class="table table-sm">
        <tbody>
            @foreach (@$quotationTerms as $quotationTerm)
                <tr>
                    <th scope="row">{{ ++$loop->index }}</th>
                    <td>
                        {{ @$quotationTerm['name'] }}
                    </td>
                    <td>
                        {{ @$quotationTerm['description'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
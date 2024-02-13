<style>
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    table tr th, table tr td {
        border: 1px #eee solid;
        padding: 5px;
    }

    .color-green {
        color: green;
    }

    .color-red {
        color: red;
    }

    .color-gray {
        color: gray;
    }

    .color-orange {
        color: orange;
    }
</style>
<table>
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($invoices as $invoice)
          <tr>    
            <td>{{$invoice->invoice_number}}</td>
            <td>${{number_format($invoice->amount, 2)}}</td>
            <td class="{{ $invoice->status->color()->getClass() }}">{{ $invoice->status->toString() }}</td>
            <td>{{$invoice->created_at}}</td>
          </tr>
        @empty
            <tr><td colspan="4">No Invoices Found</td></tr>
        @endforelse
    </tbody>
</table>
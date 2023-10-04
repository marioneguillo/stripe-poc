<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Planes disponibles
        </h2>
    </x-slot>

    <div class="py-12">
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 mx-auto">
            <table>
                <tr>
                    <th>Id</th>
                    <th>Fecha</th>
                    <th>Subscripcion Id</th>
                    <th>Precio</th>
                    <th>Recibo</th>
                    <th>Factura</th>
                </tr>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                            <td>{{ $invoice->subscription }}</td>
                            <td>{{ $invoice->total() }}</td>
                            <td><a href="/user/invoice/{{ $invoice->id }}">Download</a></td>
                            <td><a href="{{ $invoice->invoice_pdf }}">Download</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </section>
    </div>
</x-app-layout>



<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
  text-align: left;
}
#t01 {
  width: 100%;    
  background-color: #f1f1c1;
}
</style>

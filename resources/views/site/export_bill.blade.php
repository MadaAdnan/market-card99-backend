
<table>
    <tr>
        <td >
            #
        </td>
        <td >
            المنتج
        </td>
        <td >
            الإجمالي
        </td>
        <td  >
            ID / Phone
        </td>

        <td  >
            Username - Password
        </td>
        <td  >
            تاريخ الشراء
        </td>
        <td  >
            الكمية
        </td>
        <td  >
            ملاحظات
        </td>
        <td  >
            البيانات
        </td>
    </tr>

    @foreach($bills as $bill)

        <tr>
            <td >{{$loop->iteration}}</td>
            <td >{{$bill->product->name}}</td>
            <td >{{$bill->total_price}}</td>
            <td >{{$bill->customer_id}}</td>
            <td >{{$bill->customer_name}} {{$bill->customer_password}}</td>
            <td >{{$bill->created_at->format('Y-m-d ')}}</td>
            <td >{{$bill->amount}}</td>
            <td >{{$bill->customer_note}}</td>
            <td >{{$bill->data_id}}  </td>
        </tr>
    @endforeach

</table>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Items Export</title>
        <style>
            body {
                font-family: sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background: #f4f4f4;
            }
        </style>
    </head>
    <body>
        <h2>Items Export</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Name</th>
                    <th>Attachment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->brand->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->attachment }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>

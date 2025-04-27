<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
    </style>
</head>
<body>

<div class="container">



	<br/>

	<a href="{{ route('document',['download'=>'pdf']) }}">Download PDF</a>



	<table>

		<tr>

			<th>No</th>

			<th>Title</th>

			<th>Description</th>

		</tr>

		<tr>

			<td>222</td>

			<td>AAAAAAAAAAAAAAAAAAAAAA</td>

			<td>BBBBBBBBBBB bbbbbbbbbbbbbb bbbbbbbbbbbbbb</td>

		</tr>

        <tr>

			<td>333</td>

			<td>ZZZZZZZZZZZZZZZZZZZZZZZ</td>

			<td>ccccccccccc vvvvvvvvvvvvvvvvvv bbbbbbbbbbbbbb</td>

		</tr>


        <tr>

			<td>6666</td>

			<td>TTTTTTTTTTTTTTTTTTTTTTT</td>

			<td>ggggggggggg uuuuuuuuuuuuuu bbbbbbbbbbbbbb</td>

		</tr>


	</table>

</div>


</body>
</html>

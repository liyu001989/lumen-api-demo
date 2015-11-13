<html>
	<header></header>
	<body>
		<span>这样我们调用自己的api，返回的是对象</span>

		@foreach($users as $user)
		<ul>
			<li>{{$user->id}}</li>
			<li>{{$user->email}}</li>
			<li>{{$user->name}}</li>
		</ul>
		@endforeach

	</body>
</html>